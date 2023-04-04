<?php

namespace App\Services;

use App\Entity\UserInfo;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

class certificateByClustersService extends AbstractController
{


    public function __construct(SluggerInterface $slugger)
    {

    }

    public function getCertificate_2($users)
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');

        $templateProcessor = new TemplateProcessor('../public/word/Шаблон для заполнения справки.docx');
        $replacements = [];
        $replacementsWithGrand = [];
        $replacements2022 = [];

        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            $templateData = [
                'rf_subject' => is_null($user_info->getRfSubject())  ? '' : $user_info->getRfSubject()->getName() ,
                'industry' => $user_info->getDeclaredIndustry(),
                'cluster_name' => $user_info->getCluster(),
                'grant_name' => $user_info->getOrganization(),
                'intiator_name' => $user_info->getInitiatorOfCreation(),
                'employers_count' => count($user_info->getListOfEmployers()),
                'employers' => implode(", ", $user_info->getListOfEmployers()),
                'web_oo_count' => count($user_info->getListOfEdicationOrganization()),
                'web_oo' => implode(", ", $user_info->getListOfEdicationOrganization()),
                'rf_subject_funds' => $fmt->format($user_info->getFinancingFundsOfSubject() * 1000),
                'economic_sector_funds' => $fmt->format($user_info->getExtraFundsEconomicSector() * 1000),
                'oo_funds' => $fmt->format($user_info->getExtraFundsOO() * 1000),
                'base_org' => $user_info->getEducationalOrganization(),
            ];
            if($user_info->getYear() === 2022)
                array_push($replacements2022, $templateData);
            else
                if(strtolower($user_info->getEducationalOrganization()) == strtolower($user_info->getOrganization()))
                    array_push($replacements, $templateData);
                else
                    array_push($replacementsWithGrand, $templateData);

        }



        if(count($replacements) > 0)
        {

        }

        else
        {
            $templateProcessor->cloneBlock('clusterInfo', 0, true, false);
            $templateProcessor->setValue('new_title', '');
        }

        if(count($replacements2022) > 0)
        {
            $templateProcessor->cloneBlock('old', 0, true, false, $replacements2022);
            $templateProcessor->setValue('old_title', '2022 год');
        }

        else
        {
            $templateProcessor->cloneBlock('old', 0, true, false);
            $templateProcessor->setValue('old_title', '');
        }

        if(count($replacementsWithGrand) > 0)
        {
            $templateProcessor->cloneBlock('WithGrant', 0, true, false, $replacementsWithGrand);
            $templateProcessor->setValue('new_title', '2023 год');
        }
        else
        {
            $templateProcessor->cloneBlock('WithGrant', 0, true, false);
            $templateProcessor->setValue('new_title', '');
        }





        $fileName = 'Справка_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    public function getCertificate($users, $ugps = false, $zone = false)
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');

        $templateProcessor = new TemplateProcessor('../public/word/Шаблон для заполнения справки_2.docx');
        $replacements = [];

        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            $templateData = [
                'rf_subject' => is_null($user_info->getRfSubject())  ? '' : $user_info->getRfSubject()->getName() ,
                'industry' => $user_info->getDeclaredIndustry(),
                'cluster_name' => $user_info->getCluster(),
                'grant_name' => $user_info->getOrganization(),
                'intiator_name' => $user_info->getInitiatorOfCreation(),
                'employers_count' => count($user_info->getListOfEmployers()),
                'employers' => implode(", ", $user_info->getListOfEmployers()),
                'web_oo_count' => count($user_info->getListOfEdicationOrganization()),
                'web_oo' => implode(", ", $user_info->getListOfEdicationOrganization()),
                'rf_subject_funds' => $fmt->format($user_info->getFinancingFundsOfSubject() * 1000),
                'economic_sector_funds' => $fmt->format($user_info->getExtraFundsEconomicSector() * 1000),
                'oo_funds' => $fmt->format($user_info->getExtraFundsOO() * 1000),
                'base_org' => $user_info->getEducationalOrganization(),
                'year' => $user_info->getYear(),
                'zone' => is_null($user_info->getZone()) ? [] : $user_info->getZone(),
                'ugps' => is_null($user_info->getUGPS()) ? [] : $user_info->getUGPS(),

            ];
            array_push($replacements, $templateData);
        }

        $templateProcessor->cloneBlock('clusterInfo', count($replacements), true, true);
        $count = 1;
        foreach ($replacements as $replacement)
        {
            $templateProcessor->setValues(
                [
                    'rf_subject#'.$count => $replacement['rf_subject'],
                    'industry#'.$count => $replacement['industry'],
                    'cluster_name#'.$count => $replacement['cluster_name'],
                    'intiator_name#'.$count => $replacement['intiator_name'],
                    'employers_count#'.$count => $replacement['employers_count'],
                    'employers#'.$count => $replacement['employers'],
                    'rf_subject_funds#'.$count => $replacement['rf_subject_funds'],
                    'economic_sector_funds#'.$count => $replacement['economic_sector_funds'],
                ]
            );
            if(strtolower($replacement['grant_name']) == strtolower($replacement['base_org']))
            {
                $templateProcessor->cloneBlock('with_grant#'.$count, 0, true, false);
                $templateProcessor->cloneBlock('without_grant#'.$count, 1, true, false);
                $templateProcessor->setValues([
                    'base_org#'.$count => $replacement['base_org'],
                ]);
            }
            else{
                $templateProcessor->cloneBlock('with_grant#'.$count, 1, true, false);
                $templateProcessor->cloneBlock('without_grant#'.$count, 0, true, false);

                $templateProcessor->setValues([
                    'grant_name#'.$count => $replacement['grant_name'],
                    'base_org#'.$count => $replacement['base_org'],
                ]);
            }
            if($replacement['web_oo_count'] > 0)
            {
                $templateProcessor->cloneBlock('with_oo#'.$count, 1, true, false);
                $templateProcessor->setValues([
                    'web_oo_count#'.$count => $replacement['web_oo_count'],
                    'web_oo#'.$count => $replacement['web_oo'],
                ]);
            }
            else{
                $templateProcessor->cloneBlock('with_oo#'.$count, 0, true, false);
            }
            if ($replacement['year'] > 2022)
            {
                $templateProcessor->cloneBlock('with_oo_funds#'.$count, 1, true, false);
                $templateProcessor->setValues([
                    'oo_funds#'.$count => $replacement['oo_funds'],

                ]);
            }
            else
            {
                $templateProcessor->cloneBlock('with_oo_funds#'.$count, 0, true, false);
            }

            if($ugps and count($replacement['ugps']) > 0)
            {
                $templateProcessor->cloneBlock('ugps_block#'.$count, 1, true, false);
                $_ugps_str = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', implode("\n", $replacement['ugps']) );
                $templateProcessor->setValue('ugps#'.$count, $_ugps_str);

            }
            else
            {
                $templateProcessor->cloneBlock('ugps_block#'.$count, 0, true, false);
            }

            if($zone and count($replacement['zone']) > 0)
            {
                $templateProcessor->cloneBlock('zone_block#'.$count, 1, true, false);
                $_zone_str = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', implode("\n", $replacement['zone']) );
                $templateProcessor->setValue('zone#'.$count, $_zone_str);
//                $templateProcessor->replaceBlock('zone#'.$count, implode('\n', $replacement['zone']));
            }
            else
            {
                $templateProcessor->cloneBlock('zone_block#'.$count, 0, true, false);
            }



            $count++;
        }



        $fileName = 'Справка_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    public function getTableCertificate($users)
    {
        $sheet_template = "../public/excel/справка_под_задачи_минпроса.xlsx";
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $today = new \DateTime('now');
        $index = 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size'  => 11,
                'name'  => 'Times New Roman'
            ]
        ];


        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            $row =
                [
                    $index, // № п/п
                    $user_info->getRfSubject()->getDistrict(), // Округ
                    $user_info->getRfSubject()->getName(),// Регион
                    $user_info->getDeclaredIndustry(), // Отрасль
                    $user_info->getCluster(), // Наименование центра (кластера)
                    $user_info->getInitiatorOfCreation(), // Инициатор создания центра
                    $user_info->getOrganization(), // Базовая образовательная организация (грантополучатель)
                    $this->arrayToStringList( $user_info->getListOfEmployers()), // Работодатели
                    $this->arrayToStringList($user_info->getListOfEdicationOrganization()), // Образовательные организации
                    $user_info->getExtraFundsEconomicSector() * 1000, // Объем внебюджетных средств, направляемых участниками центра из числа организаций, действующих в реальном секторе экономики
                    $user_info->getFinancingFundsOfSubject() * 1000, // Объём финансирования из средств субъекта РФ (руб.)
                    $user_info->getExtraFundsOO() * 1000, // Объём финансирования из средств субъекта РФ (руб.)
                    $this->arrayToStringList( $user_info->getUGPS()), // Наименование профессий и специальностей, реализуемых в кластере
                    $this->arrayToStringList( $user_info->getZone()), // Зоны по виду работ, созданные в рамках проекта
                    $this->arrayToStringList( $user_info->getListOfAnotherOrganization()) // Иные организации
                ]
            ;
            $row_arr = ['J', 'K', 'L'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.($index+1))->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $sheet->fromArray($row, '', 'A'.($index+1));
            $index++;
        }
//        $result_row = [
//            '',
//            count(array_unique($sheet->rangeToArray('B2:B'.$index))),
//            count(array_unique($sheet->rangeToArray('C2:C'.$index))),
//            count(array_unique($sheet->rangeToArray('D2:D'.$index))),
//            count(array_unique($sheet->rangeToArray('E2:E'.$index))),
//
//        ];
        $result_row_title = [
            '',
            'Итого округов',
            'Итого регион',
            'Итого отраслей',
            'Итого кластеров',
            '',
            '',
            'Итого работодателей',
            'Итого образовательных организаций',
            'Итого средств от реального сектора экономики',
            'Итого средств от субъектов',
            'Итого средств от ОО',
            'Итого профессий и специальностей',
            'Итого зон по виду работ',
            '',
        ];
        $index++;
        $sheet->fromArray($result_row_title, '', 'A'.($index));

        $index++;
//        $sheet->fromArray($result_row, '', 'A'.($index));
        $rangeTotal = 'A2:O'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($index)->setRowHeight(-1);

        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Карта готовности(ремонт)_'.$today->format('d-m-Y').'.xlsx';

        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function arrayToStringList($arr)
    {
        $str = "";
        $index = 1;
        if(count($arr) > 0)
        {
//            if(count($arr) === 1)
//                return $arr[0];

            foreach ($arr as $a)
            {
                $str = $str."$index) ".$a."\n";
                $index++;
            }
        }



        return $str;
    }

}