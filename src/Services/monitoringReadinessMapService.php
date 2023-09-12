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

class monitoringReadinessMapService extends AbstractController
{


    public function __construct(SluggerInterface $slugger)
    {

    }

    public function getCertificate(User $user, $save = false, $file = '../public/word/Заключение_по_мониторинговому_выезду.docx')
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

        // Заголовок
        $replacements = [
            'cluster_name' => $user_info->getCluster(),
            'cluster_base' => $user_info->getCluster(),
            'industry' => $user_info->getDeclaredIndustry(),
            'rf_subject' => $user_info->getRfSubject()->getName(),

        ];
        $templateProcessor->setValues($replacements);

        // Общие зоны
//        dd(count($addresses));
        $templateProcessor->cloneBlock('common_zone', count($addresses), true, true);
        $count_zones = 1;
        foreach ($addresses as $address)
        {
            $templateProcessor->setValue('address#'.$count_zones, $address->getAddresses());

            $values = [];
            $is_count = 1;
            foreach ($address->getSortedClusterCommonZones() as $zone)
            {
                $repair = $zone->getZoneRepair();
                $arr = [
                    'is_num#'.$count_zones => $is_count,
                    'name#'.$count_zones => $zone->getName(),
                    'repair#'.$count_zones => $repair->getTotalPercentage(),
                    'end_date#'.$count_zones => is_null($repair->getEndDate()) ? "" : $repair->getEndDate()->format('d.m.Y') ,

                ];
                array_push($values, $arr);
                $is_count++;
            }
            $templateProcessor->cloneRowAndSetValues('is_num#'.$count_zones, $values);
            $count_zones++;
        }
//        foreach ($zones as $zone)
//        {
//            $templateProcessor->setValue('address#'.$count_zones, $zone->getName());
//            $values = [];
//            $is_count = 1;
//            foreach ($zone->getZoneInfrastructureSheets() as $sheet)
//            {
//                $arr = [
//                    'is_num#'.$count_zones => $is_count,
//                    'name#'.$count_zones => $sheet->getName(),
//                    'type#'.$count_zones => $sheet->getType(),
//                    'count#'.$count_zones => $sheet->getTotalNumber(),
//                    'funds#'.$count_zones => "",
//
//                ];
//                array_push($values, $arr);
//                $is_count++;
//            }
//            $templateProcessor->cloneRowAndSetValues('is_num#'.$count_zones, $values);
//            $count_zones++;
//        }

        // Зоны с ИЛами
        $zones = $user->getSortedWorkZones();

        $templateProcessor->cloneBlock('zone', count($zones), true, true);

        $count_zones = 1;
        foreach ($zones as $zone)
        {
            $templateProcessor->setValue('zone_name#'.$count_zones, $zone->getName());
            $values = [];
            $is_count = 1;
            foreach ($zone->getZoneInfrastructureSheets() as $sheet)
            {
                $arr = [
                    'is_num#'.$count_zones => $is_count,
                    'name#'.$count_zones => $sheet->getName(),
                    'type#'.$count_zones => $sheet->getType(),
                    'count#'.$count_zones => $sheet->getTotalNumber(),
                    'funds#'.$count_zones => "",

                ];
                array_push($values, $arr);
                $is_count++;
            }
            $templateProcessor->cloneRowAndSetValues('is_num#'.$count_zones, $values);
            $count_zones++;
        }

        $fileName = 'мониторинг_'.
            $user->getUserInfo()->getEducationalOrganization().
            '_'.$today->format('d.m.Y').
            '.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }




}