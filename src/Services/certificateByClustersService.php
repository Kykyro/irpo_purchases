<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCertificate($users, $options = [])
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');
        $ugps = in_array('ugps', $options);
        $zone = in_array('zone', $options);

        $templateProcessor = new TemplateProcessor('../public/word/Шаблон для заполнения справки_2 (копия).docx');
        $replacements = [];

        foreach ($users as $user)
        {
            $templateData = $this->getTemplateData($user, $fmt);
            array_push($replacements, $templateData);
        }

        $templateProcessor->cloneBlock('clusterInfo', count($replacements), true, true);
        $count = 1;

        foreach ($replacements as $replacement)
        {
            $user = $replacement['user'];
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

            $this->setBlockWithList(
                    $templateProcessor,
                    $replacement['ugps'],
                    $ugps,
                    $count,
                    'ugps_block',
                    'ugps'
            );

            $this->setBlockWithList(
                    $templateProcessor,
                    $replacement['zone'],
                    $zone,
                    $count,
                    'zone_block',
                    'zone'
            );

            $blocks = [
                'fed_budget' => in_array('budget', $options),
                'reg_budget' => in_array('budget', $options),
                'empl_budget' => in_array('budget', $options),
                'extra_budget' => in_array('budget', $options),
                'repair_block' => in_array('repair', $options),
                'eqp_block' => in_array('equipment', $options)
            ];
            foreach ($blocks as $block => $show)
            {
                if($show)
                    $templateProcessor->cloneBlock($block.'#'.$count, 0, true, false,
                        $this->getDataForOptionalBlocks($block, $user, $count, $fmt));
                else
                    $templateProcessor->cloneBlock($block.'#'.$count, 0, true, false);

            }

            $count++;
        }



        $fileName = 'Справка_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }
    public function getTemplateData($user, $fmt)
    {
        $user_info = $user->getUserInfo();
        return [
            'user' => $user,
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
    }

    public function setBlockWithList($templateProcessor, $value, $isTrue, $count, $block, $replace){
        if($isTrue and count($value) > 0)
        {
            $templateProcessor->cloneBlock($block.'#'.$count, 1, true, false);
            $_ugps_str = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', implode("\n", $value) );
            $templateProcessor->setValue($replace.'#'.$count, $_ugps_str);
        }
        else
        {
            $templateProcessor->cloneBlock($block.'#'.$count, 0, true, false);
        }
    }

    public function getDataForOptionalBlocks($block, $user, $count, $fmt)
    {
        if($user->getuserInfo()->getYear() < 2023)
            return null;
        $_today = new \DateTime('now');
        $today = $_today->format('d.m.Y');
        if($block == 'fed_budget')
        {
            $values = [
                'initial' => 0,
                'contract' => 0,
                'fact' => 0,
            ];
            $purchases = $this->entityManager
                ->getRepository(ProcurementProcedures::class)
                ->findByUser($user);
            foreach ($purchases as $purchase)
            {
                $status = $purchase->getPurchasesStatus($_today);
                if($status == 'cancelled' or $status == 'planning')
                    continue;

                if($status == 'announced')
                {
                    $values['initial'] += $purchase->getInitialFederalFunds();
                }
                if($status == 'contract')
                {
                    $values['contract'] += $purchase->getFinFederalFunds();
                    $values['fact'] += $purchase->getFactFederalFunds();
                }
            }
            return [[
                'today_budget#'.$count => $today,
                'fed_budget_contract#'.$count => $fmt->format($values['contract']),
                'fed_budget_initial#'.$count => $fmt->format($values['initial']),
                'fed_budget_fact#'.$count => $values['fact'] > 0 ?
                    $fmt->format($values['fact']) : 'на текущий момент равно нулю',
            ]];
        }
        if($block == 'reg_budget')
        {
            if ($user->getuserInfo()->getFinancingFundsOfSubject() == 0)
                return null;
            $values = [
                'initial' => 0,
                'contract' => 0,
                'fact' => 0,
            ];
            $purchases = $this->entityManager
                ->getRepository(ProcurementProcedures::class)
                ->findByUser($user);
            foreach ($purchases as $purchase)
            {
                $status = $purchase->getPurchasesStatus($_today);
                if($status == 'cancelled' or $status == 'planning')
                    continue;

                if($status == 'announced')
                {
                    $values['initial'] += $purchase->getInitialFundsOfSubject();
                }
                if($status == 'contract')
                {
                    $values['contract'] += $purchase->getFinFundsOfSubject();
                    $values['fact'] += $purchase->getFactFundsOfSubject();
                }
            }
            return [[
                'reg_budget_contract#'.$count => $fmt->format($values['contract']),
                'reg_budget_initial#'.$count => $fmt->format($values['initial']),
                'reg_budget_fact#'.$count => $values['fact'] > 0 ?
                    $fmt->format($values['fact']) : 'на текущий момент равно нулю',
            ]];
        }
        if($block == 'empl_budget')
        {
            if ($user->getuserInfo()->getExtraFundsEconomicSector() == 0)
                return null;
            $values = [
                'initial' => 0,
                'contract' => 0,
                'fact' => 0,
            ];
            $purchases = $this->entityManager
                ->getRepository(ProcurementProcedures::class)
                ->findByUser($user);
            foreach ($purchases as $purchase)
            {
                $status = $purchase->getPurchasesStatus($_today);
                if($status == 'cancelled' or $status == 'planning')
                    continue;

                if($status == 'announced')
                {
                    $values['initial'] += $purchase->getInitialEmployersFunds();
                }
                if($status == 'contract')
                {
                    $values['contract'] += $purchase->getFinEmployersFunds();
                    $values['fact'] += $purchase->getFactEmployersFunds();
                }
            }
            return [[
                'empl_budget_contract#'.$count => $fmt->format($values['contract']),
                'empl_budget_initial#'.$count => $fmt->format($values['initial']),
                'empl_budget_fact#'.$count => $values['fact'] > 0 ?
                    $fmt->format($values['fact']) : 'на текущий момент равно нулю',
            ]];
        }
        if($block == 'extra_budget')
        {
            if ($user->getuserInfo()->getExtraFundsOO() == 0)
                return null;
            $values = [
                'initial' => 0,
                'contract' => 0,
                'fact' => 0,
            ];
            $purchases = $this->entityManager
                ->getRepository(ProcurementProcedures::class)
                ->findByUser($user);
            foreach ($purchases as $purchase)
            {
                $status = $purchase->getPurchasesStatus($_today);
                if($status == 'cancelled' or $status == 'planning')
                    continue;

                if($status == 'announced')
                {
                    $values['initial'] += $purchase->getInitialEducationalOrgFunds();
                }
                if($status == 'contract')
                {
                    $values['contract'] += $purchase->getFinFundsOfEducationalOrg();
                    $values['fact'] += $purchase->getFactFundsOfEducationalOrg();
                }
            }
            return [[
                'extra_budget_contract#'.$count => $fmt->format($values['contract']),
                'extra_budget_initial#'.$count => $fmt->format($values['initial']),
                'extra_budget_fact#'.$count => $values['fact'] > 0 ?
                    $fmt->format($values['fact']) : 'на текущий момент равно нулю',
            ]];
        }
        if($block == 'repair_block')
        {

            $repairCommon = round($user->getMidRepairByCommon(), 2);
            $repairZone = round($user->getMidRepairByZone(), 2);
            return [[
              'today_repair#'.$count => $today,
              'repair_common#'.$count => $repairCommon < 100 ? $repairCommon.' %' : 'завершены полностью',
              'repair_zone#'.$count => $repairZone  < 100 ? $repairZone.' %' : 'завершены полностью',
              'repair_all#'.$count => ($repairCommon + $repairZone)/2 < 100 ? round(($repairCommon + $repairZone)/2, 2).' %' : 'завершены полностью',
              'repair_deadline#'.$count => $user->getDeadlineForCompletionOfRepairs(),
            ]];
        }
        if($block == 'eqp_block')
        {
            $eqp = $user->getCountOfEquipment();
            $fact = $eqp['total'] > 0 ? round(($eqp['fact']/$eqp['total'])*100, 2) : 0;
            $putInOperation = $eqp['total'] > 0 ?  round(($eqp['putInOperation']/$eqp['total'])*100, 2) : 0;
            return [[
                'today_eqp#'.$count => $today,
                'eqp_fact#'.$count => $fact == 0 ? 'на текущий момент равна нулю' : $fact.' %',
                'eqp_in_operation#'.$count => $putInOperation,
                'eqp_deadline#'.$count => $user->getEquipmentDeliveryDeadline(),
            ]];
        }
        return [];
    }


//    EXCELE
    public function getTableCertificate($users, $ugps=[], $employeers=[], $zones=[])
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
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];

        $district_arr = [];
        $region_arr = [];
        $clusters_arr = [];
        $industry_arr = [];
        $employers_arr = [];
        $edicationOrganization_arr = [];
        $UGPS_arr = [];
        $zone_arr = [];
//        $anotherOrganization_arr = [];
        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            array_push($district_arr, $user_info->getRfSubject()->getDistrict());
            array_push($region_arr, $user_info->getRfSubject()->getName());
            array_push($clusters_arr, $user_info->getCluster());
            array_push($industry_arr, $user_info->getDeclaredIndustry());
            $employers_arr = array_merge($employers_arr,
                (is_null($user_info->getListOfEmployers()) ? [] : $user_info->getListOfEmployers()));
            $edicationOrganization_arr = array_merge($edicationOrganization_arr,
                (is_null($user_info->getListOfEdicationOrganization())) ? [] : $user_info->getListOfEdicationOrganization());
            $UGPS_arr = array_merge($UGPS_arr,  (is_null($user_info->getUGPS()) ? [] : $user_info->getUGPS()));
            $zone_arr = array_merge($zone_arr,  (is_null($user_info->getZone()) ? [] : $user_info->getZone()));
//            $anotherOrganization_arr = array_merge($anotherOrganization_arr,  $user_info->getListOfAnotherOrganization());

            $_ugps = $user_info->getUGPS();
            $_employeers = $user_info->getListOfEmployers();
            $_zones = $user_info->getZone();

            if(count($ugps) > 0)
            {
                $_ugps = $this->findFillter($ugps, $_ugps);
            }
            if(count($employeers) > 0)
            {
                $_employeers = $this->findFillter($employeers, $_employeers);
            }
            if(count($zones) > 0)
            {
                $_zones = $this->findFillter($zones, $_zones);
            }

            $row =
                [
                    $index, // № п/п
                    $user_info->getRfSubject()->getDistrict(), // Округ
                    $user_info->getRfSubject()->getName(),// Регион
                    $user_info->getDeclaredIndustry(), // Отрасль
                    $user_info->getCluster(), // Наименование центра (кластера)
                    $user_info->getInitiatorOfCreation(), // Инициатор создания центра
                    $user_info->getEducationalOrganization(),// Базовая образовательная организация
                    $user_info->getOrganization(),//(грантополучатель)
//                    $user_info->getOrganization() != $user_info->getEducationalOrganization() ?
//                    $user_info->getEducationalOrganization()." (".$user_info->getOrganization().")" :
//                    $user_info->getEducationalOrganization(),

                    $this->arrayToStringList( $_employeers), // Работодатели
                    $this->arrayToStringList($user_info->getListOfEdicationOrganization()), // Образовательные организации
                    $user_info->getExtraFundsEconomicSector() * 1000, // Объем внебюджетных средств, направляемых участниками центра из числа организаций, действующих в реальном секторе экономики
                    $user_info->getFinancingFundsOfSubject() * 1000, // Объём финансирования из средств субъекта РФ (руб.)
                    $user_info->getExtraFundsOO() * 1000, // Объём финансирования из средств субъекта РФ (руб.)
                    $this->arrayToStringList( $_ugps), // Наименование профессий и специальностей, реализуемых в кластере
                    $this->arrayToStringList( $_zones), // Зоны по виду работ, созданные в рамках проекта
                    $this->arrayToStringList( $user_info->getListOfAnotherOrganization()), // Иные организации
                    $user_info->getYear(),
                    $user_info->getCity()
                ]
            ;
            $row_arr = ['M', 'K', 'L'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.($index+1))->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $sheet->fromArray($row, '', 'A'.($index+1));

            $index++;
            $sheet->getRowDimension($index)->setRowHeight(-1);
        }
//        dd(($sheet->rangeToArray('B2:B'.$index, null, true, true, false, false)));
        $result_row = [
            '',
            count(array_unique($district_arr)),
            count(array_unique($region_arr)),
            count(array_unique($industry_arr)),
            count(array_unique($clusters_arr)),
            '',
            '',
            '',
            count(array_unique($employers_arr)),
            count(array_unique($edicationOrganization_arr)),
            "=SUM(J2:J$index)",
            "=SUM(K2:K$index)",
            "=SUM(L2:L$index)",
            count(array_unique($UGPS_arr)),
            count(array_unique($zone_arr)),

        ];
        $result_row_title = [
            '',
            'Итого округов',
            'Итого регионов',
            'Итого отраслей',
            'Итого кластеров',
            '',
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
        $row_arr = ['J', 'K', 'L'];

        $index++;
        $sheet->fromArray($result_row, '', 'A'.($index));
        foreach ($row_arr as $j){
            $sheet->getStyle($j.($index))->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        }
        $rangeTotal = 'A2:R'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($index)->setRowHeight(-1);

        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Справка_по_кластерам_'.$today->format('d-m-Y').'.xlsx';

        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
    public  function  findFillter($arr, $find_arr)
    {
        $_arr = [];

        foreach ($find_arr as $i)
        {
            foreach ($arr as $j)
            {
                if(str_contains(strtolower($i), strtolower($j)))
                {
                    array_push($_arr, $i);
                }
            }
        }
        return $_arr;
    }
    public function arrayToStringList($arr)
    {
        if(is_null($arr))
            return '';
        $str = "";
        $index = 1;
//        if(count($arr) > 0)
//        {
//            if(count($arr) === 1)
//                return $arr[0];

        foreach ($arr as $a)
        {
            $str = $str."$index) ".$a."\n";
            $index++;
        }
//        }



        return $str;
    }

}