<?php

namespace App\Services;

use App\Entity\User;
use NumberFormatter;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class certificateReadinessMap extends AbstractController
{


    public function __construct(SluggerInterface $slugger)
    {

    }

    public function getCertificate(User $user)
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');
        $user_info = $user->getUserInfo();
        $addresses = $user->getClusterAddresses();
        $workZoneCount = $user->getCountOfWorkZone();

        $templateProcessor = new TemplateProcessor('../public/word/Карта готовности Кластеры.docx');


        // Заголовок
        $replacements = [
            'cluster' => $user_info->getCluster(),
            'industry' => $user_info->getDeclaredIndustry(),
            'day' => $today->format('d'),
            'month' => $today->format('m'),
            'year' => $today->format('Y'),
        ];
        $templateProcessor->setValues($replacements);

        // блок Проведение ремонтных работ и брендирование.
        $templateProcessor->cloneBlock('block_i', count($addresses), true, true);

        $countAddres = 1;
        foreach ($addresses as $addres){
            $zones = $addres->getClusterZones();
            $zoneCount = 1;
            $templateProcessor->setValue('adress#'.$countAddres, $addres->getAddresses());
            $templateProcessor->cloneRow('row#'.$countAddres, count($zones));
            foreach ($zones as $zone)
            {
                $zone_repait = $zone->getZoneRepair();
                $templateProcessor->setValue('row#'.$countAddres.'#'.$zoneCount, $zoneCount);
                $templateProcessor->setValue('zone_name#'.$countAddres.'#'.$zoneCount, $zone->getName());
                $templateProcessor->setValue('repair_procent#'.$countAddres.'#'.$zoneCount, $zone_repait->getTotalPercentage());
                $templateProcessor->setValue('repair_deadline#'.$countAddres.'#'.$zoneCount,
                    ($zone_repait->getEndDate()) ? $zone_repait->getEndDate()->format('d.m.Y') : '');
                $templateProcessor->setValue('comment#'.$countAddres.'#'.$zoneCount, $zone_repait->getComment());

                $zoneCount++;
            }


            $countAddres++;
        }
        // блок Оснащение учебно-производственных площадок оборудованием.
//        $countAddres = 0;
        $templateProcessor->cloneBlock('block_ii', 1, true, false);
        $templateProcessor->cloneRow('row_i', $workZoneCount);
        $zoneCount = 1;

        foreach ($addresses as $addres){
            $zones = $addres->getClusterZones();


            foreach ($zones as $zone)
            {
                if($zone->getType()->getName() == "Зона по видам работ" and !$zone->isDoNotTake())
                {
                    $equipment = $zone->getCountOfEquipmentByType();
                    $equipment_count = $zone->getCountOfEquipment();
                    $comments = $zone->getAllComments();
                    $comments = (count($comments) > 0) ? implode("\n", $comments) : '';
                    $comments =   str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', $comments );;

                    $_furniture = ($equipment['furniture'] > 0) ? ($equipment['furniture_put']/$equipment['furniture'])*100 : 0;
                    $_PO = ($equipment['PO'] > 0) ? ($equipment['PO_put']/$equipment['PO'])*100 : 0;
                    $_equipment = ($equipment['equipment'] > 0) ? ($equipment['equipment_put']/$equipment['equipment'])*100 : 0;
                    $equipment_count = ($equipment_count['total'] > 0) ? ($equipment_count['putInOperation']/$equipment_count['total'])*100 : 0;

                    $templateProcessor->setValue('row_i#'.$zoneCount, $zoneCount);
                    $templateProcessor->setValue('zone_name_ii#'.$zoneCount, $zone->getName());
                    $templateProcessor->setValue('deadline_ii#'.$zoneCount,
                        ($zone->getMaxEquipmentDeliveryDeadline()) ?
                            $zone->getMaxEquipmentDeliveryDeadline()->format('d.m.Y') :
                            '');

                    $templateProcessor->setValue('furniture#'.$zoneCount, round($_furniture, 2));
                    $templateProcessor->setValue('PO#'.$zoneCount, round($_PO,2));
                    $templateProcessor->setValue('equipment#'.$zoneCount, round($_equipment, 2));
                    $templateProcessor->setValue('equipment_all#'.$zoneCount, round($equipment_count,2));
                    $templateProcessor->setValue('comment#'.$zoneCount, $comments);
                    $zoneCount++;
                }

            }
        }

        $fileName = 'Карта готовности_'.
            $user->getUserInfo()->getEducationalOrganization().
            '_'.$today->format('d.m.Y').
            '.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }



}