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

    public function getCertificate(User $user, $save = false, $file = '../public/word/Карта готовности Кластеры.docx')
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');
        $user_info = $user->getUserInfo();
        $addresses = $user->getClusterAddresses();
        if(is_null($addresses))
        {
            return false;
        }
        $workZoneCount = $user->getCountOfWorkZone();

        $templateProcessor = new TemplateProcessor($file);

        $title = '';
            if(in_array('ROLE_REGION', $user->getRoles()))
            {
                $title = 'образовательно-производственного центра (кластера)';
            }
            elseif (in_array('ROLE_SMALL_CLUSTERS_LOT_1', $user->getRoles()))
            {
                $title = 'образовательного кластера среднего профессионального образования';
            }
            elseif (in_array('ROLE_SMALL_CLUSTERS_LOT_2', $user->getRoles()))
            {
                $title = 'образовательного кластера среднего профессионального образования';
            }
            elseif (in_array('ROLE_BAS', $user->getRoles()))
            {
                $title = $user_info->getRfSubject()->getName();
                $file = '../public/word/Шаблон_карта_готовности_БАС.docx';
            }
            else{
                throw new Exception('Ошибка роли');
            }

        $templateProcessor = new TemplateProcessor($file);
        // Заголовок
        $replacements = [
            'cluster' => $user_info->getCluster(),
            'industry' => $user_info->getDeclaredIndustry(),
            'day' => $today->format('d'),
            'month' => $today->format('m'),
            'year' => $today->format('Y'),
            'title' => $title,
        ];
        $templateProcessor->setValues($replacements);

        // блок Проведение ремонтных работ и брендирование.
        $templateProcessor->cloneBlock('block_i', count($addresses), true, true);

        $countAddres = 1;
        foreach ($addresses as $addres){
            $zones = $addres->getSortedClusterZones();
            $zoneCount = 1;
            $templateProcessor->setValue('adress#'.$countAddres, $addres->getAddresses());
            $templateProcessor->cloneRow('zone_name#'.$countAddres, count($zones));
            foreach ($zones as $zone)
            {
                $zone_repait = $zone->getZoneRepair();
//                $templateProcessor->setValue('row#'.$countAddres.'#'.$zoneCount, $zoneCount);
                $templateProcessor->setValue('zone_name#'.$countAddres.'#'.$zoneCount, $zone->getName());
                $templateProcessor->setValue('repair_procent#'.$countAddres.'#'.$zoneCount,
                    str_replace('.', ',', round($zone_repait->getTotalPercentage(), 1)));
                $templateProcessor->setValue('repair_deadline#'.$countAddres.'#'.$zoneCount,
                    ($zone_repait->getEndDate()) ? $zone_repait->getEndDate()->format('d.m.Y') : '-');
                $templateProcessor->setValue('comment#'.$countAddres.'#'.$zoneCount, $zone_repait->getComment());

                $zoneCount++;
            }


            $countAddres++;
        }
        // блок Оснащение учебно-производственных площадок оборудованием.
//        $countAddres = 0;
        $templateProcessor->cloneBlock('block_ii', 1, true, false);
        $templateProcessor->cloneRow('zone_name_ii', $workZoneCount);
        $zoneCount = 1;


        $zones = $user->getSortedZones();


        foreach ($zones as $zone)
        {
            if($zone->getType()->getName() == "Зона по видам работ" and !$zone->isDoNotTake())
            {
                $equipment = $zone->getCountOfEquipmentByType();
                $equipment_count = $zone->getCountOfEquipment();

                $_furniture = ($equipment['furniture'] > 0) ? ($equipment['furniture_fact']/$equipment['furniture'])*100 : 0;
                $_PO = ($equipment['PO'] > 0) ? ($equipment['PO_fact']/$equipment['PO'])*100 : 0;
                $_equipment = ($equipment['equipment'] > 0) ? ($equipment['equipment_fact']/$equipment['equipment'])*100 : 0;
                $equipment_count = ($equipment_count['total'] > 0) ? ($equipment_count['putInOperation']/$equipment_count['total'])*100 : 0;

                $templateProcessor->setValue('zone_name_ii#'.$zoneCount, $zone->getName());
                $templateProcessor->setValue('deadline_ii#'.$zoneCount,
                    ($zone->getMaxEquipmentDeliveryDeadline()) ?
                        $zone->getMaxEquipmentDeliveryDeadline()->format('d.m.Y') :
                        '-');

                $templateProcessor->setValue('furniture#'.$zoneCount,
                    ($equipment['furniture'] > 0) ?
                    "Мебель на ".str_replace('.', ',', round($_furniture, 1))." (%); </w:t><w:br/><w:t xml:space=\"preserve\">" :
                    "");
                $templateProcessor->setValue('PO#'.$zoneCount,
                    ($equipment['PO'] > 0) ?
                    "Программное обеспечение на ".str_replace('.', ',', round($_PO,1))." (%); </w:t><w:br/><w:t xml:space=\"preserve\">"  :
                    "");
                $templateProcessor->setValue('equipment#'.$zoneCount,
                    ($equipment['equipment'] > 0) ?
                    "Оборудование на ".str_replace('.', ',', round($_equipment, 1))." (%); </w:t><w:br/><w:t xml:space=\"preserve\">"  :
                    "");
                $templateProcessor->setValue('equipment_all#'.$zoneCount,
                    str_replace('.', ',', round($equipment_count,1)));

//                $templateProcessor->setValue('comment#'.$zoneCount, $comments);

                $zoneCount++;
            }

        }

        if($save)
        {
            $fileName = 'Карта готовности_'
                .$today->format('d-m-Y').'_'.uniqid().'.docx';

            if (!file_exists($this->getParameter('readiness_map_archive_directory'))) {
                mkdir($this->getParameter('readiness_map_archive_directory'), 0777, true);
            }

            $templateProcessor->saveAs($this->getParameter('readiness_map_archive_directory').'/'.$fileName);

            return $fileName;
        }
        else
        {
            if (in_array('ROLE_BAS', $user->getRoles()))
            {
                $fileName = 'Карта готовности_'.
                    $user_info->getRfSubject()->getName().
                    '_'.$today->format('d.m.Y').
                    '.docx';
            }
            else
            {
                $fileName = 'Карта готовности_'.
                    $user->getUserInfo()->getEducationalOrganization().
                    '_'.$today->format('d.m.Y').
                    '.docx';
            }

            $filepath = $templateProcessor->save();

            return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }


    }




}