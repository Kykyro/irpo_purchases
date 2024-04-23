<?php

namespace App\Services\cofinancing;

use App\Entity\ClusterAddresses;
use App\Entity\CofinancingComment;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchasesDump;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CofinancingTableService extends AbstractController
{

    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function downloadTable($year, $role, $tags)
    {
        $sheet_template = $this->getParameter('cofinancing_sertificate_table_directory');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $today = new \DateTime('now');
        $index = 1;
//        dd($tags);
        $users = $this->em->getRepository(User::class)->getUsersByYearRoleTags($year, $role, $tags);

        $pages = [
            'Средства РД',
            'Средства субъекта РФ',
            'Средства ОО',
        ];
        $rowCount = 2;
        $row_arr = ['E', 'F'];

        foreach ($users as $user)
        {
            $cofinancingSum = $user->getCofinancingSum();
            $userInfo = $user->getUserInfo();
            $sheet = $spreadsheet->getSheetByName('Средства РД');
            $fundsComment = $user->getCofinancingComment();
            if(is_null($fundsComment))
                $fundsComment = new CofinancingComment($user);

            $row = [
                $index,
                $userInfo->getRfSubject()->getName(),
                $userInfo->getDeclaredIndustry(),
                $userInfo->getEducationalOrganization(),
                $userInfo->getExtraFundsEconomicSector()*1000,
                $cofinancingSum['employersFunds'],
                "=F$rowCount/E$rowCount",
                $fundsComment->getEmployerFunds(),
                $userInfo->getCurator(),
            ];
            $sheet->fromArray($row, null, 'A'.$rowCount, true);
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $sheet->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);


            $sheet = $spreadsheet->getSheetByName('Средства субъекта РФ');

            $row = [
                $index,
                $userInfo->getRfSubject()->getName(),
                $userInfo->getDeclaredIndustry(),
                $userInfo->getEducationalOrganization(),
                $userInfo->getExtraFundsOO()*1000,
                $cofinancingSum['subjectFunds'],
                "=F$rowCount/E$rowCount",
                $fundsComment->getRegionFunds(),
                $userInfo->getCurator(),
            ];
            $sheet->fromArray($row, null, 'A'.$rowCount, true);
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $sheet->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);


            $sheet = $spreadsheet->getSheetByName('Средства ОО');
            $row = [
                $index,
                $userInfo->getRfSubject()->getName(),
                $userInfo->getDeclaredIndustry(),
                $userInfo->getEducationalOrganization(),
                $userInfo->getFinancingFundsOfSubject()*1000,
                $cofinancingSum['OOFunds'],
                "=F$rowCount/E$rowCount",
                $fundsComment->getEducationFunds(),
                $userInfo->getCurator(),
            ];
            $sheet->fromArray($row, null, 'A'.$rowCount, true);
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $sheet->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);

            $rowCount++;
            $index++;
        }



        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size'  => 14,
                'name'  => 'Times New Roman'
            ]
        ];

        foreach ($pages as $page)
        {
            $sheet = $spreadsheet->getSheetByName($page);
            $row = [
                "Итоговая сумма:",
                "=SUM(E2:E$rowCount)",
                "=SUM(F2:F$rowCount)",
                "=AVERAGE(G2:G$rowCount)",
            ];
            $sheet->fromArray($row, null, 'D'.($rowCount+1), true);
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $sheet->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);

            $end_cell = $sheet->getHighestRow();
            $rangeTotal = 'A2:I'.$end_cell;
            $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
            $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A:L')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A:L')->getAlignment()->setVertical('center');
        }





        // Запись файла
        $writer = new Xlsx($spreadsheet);

//        if($save)
//        {
//            $fileName = $fileName.'_'.uniqid().'.xlsx';
//            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
//                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
//            }
//
//            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);
//
//            return $fileName;
//        }
//        else{
            $fileName = 'Софинансирование_'.$year.'_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
//        }

    }
    public function downloadTableAll($year, $role)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('uvas_sertificate_table_directory');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $today = new \DateTime('now');
        $index = 1;



        $sheet->setCellValue('A2',
            "Справка об оснащении специализированных классов (кружков) и центров практической подготовки БПЛА");
        $rowCount = 5;
        $rows = [];
        $rowNames = [];
        $users = $this->em->getRepository(User::class)->findByYearAndRole($year, $role);
        foreach ($users as $user)
        {
            $equipments = $user->getUAVsTypeEquipment();
            foreach ($equipments as $equipment)
            {
                if(!array_key_exists($equipment->getName(), $rowNames))
                {
                    $rowNames[$equipment->getName()] = [
                        $equipment->getDeliveredCount() ?  $equipment->getDeliveredCount() : 0,
                        $equipment->getDeliveredSum() ? $equipment->getDeliveredSum() : 0,
                        $equipment->getContractedCount() ? $equipment->getContractedCount() : 0,
                        $equipment->getContractedSum() ? $equipment->getContractedSum() : 0,
                        $equipment->getPurchaseCount() ? $equipment->getPurchaseCount() : 0,
                        $equipment->getPurchaseSum() ? $equipment->getPurchaseSum() : 0,
                        "",
                        $equipment->getPlanSum() ? $equipment->getPlanSum() : 0,
                    ];
                }
                else
                {
                    $rowNames[$equipment->getName()] = [
                        $rowNames[$equipment->getName()][0]+$equipment->getDeliveredCount(),
                        $rowNames[$equipment->getName()][1]+$equipment->getDeliveredSum(),
                        $rowNames[$equipment->getName()][2]+$equipment->getContractedCount(),
                        $rowNames[$equipment->getName()][3]+$equipment->getContractedSum(),
                        $rowNames[$equipment->getName()][4]+$equipment->getPurchaseCount(),
                        $rowNames[$equipment->getName()][5]+$equipment->getPurchaseSum(),
                        "",
                        $rowNames[$equipment->getName()][7]+$equipment->getPlanSum(),
                    ];
                }
            }
        }
        foreach ($rowNames as $key => $row )
        {
            $sheet->fromArray([$index, $key], null, 'A'.$rowCount, true);
            $sheet->fromArray($row, null, 'C'.$rowCount, true);
            $rowCount++;
            $index++;
        }




        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size'  => 14,
                'name'  => 'Times New Roman'
            ]
        ];
        $end_cell = $sheet->getHighestRow();
        $rangeTotal = 'A5:O'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:O')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:O')->getAlignment()->setVertical('center');




        // Запись файла
        $writer = new Xlsx($spreadsheet);

//        if($save)
//        {
//            $fileName = $fileName.'_'.uniqid().'.xlsx';
//            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
//                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
//            }
//
//            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);
//
//            return $fileName;
//        }
//        else{
            $fileName = 'Свод_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
//        }

    }
    public function downloadTableAllByRegion($year, $role)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('uvas_sertificate_table_directory');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $today = new \DateTime('now');
        $index = 1;



        $sheet->setCellValue('A2',
            "Справка об оснащении специализированных классов (кружков) и центров практической подготовки БПЛА");
        $rowCount = 5;

        $users = $this->em->getRepository(User::class)->findByYearAndRole($year, $role);
        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            $equipments = $user->getUAVsTypeEquipment();
            $sheet->fromArray([$userInfo->getRfSubject()->getName()], null, 'A'.$rowCount, true);
            $sheet->mergeCells("A$rowCount:L$rowCount");
            $rowCount++;
            $index=1;
            foreach ($equipments as $equipment)
            {
                $row = [
                    $index,
                    $equipment->getName(),
                    $equipment->getDeliveredCount(),
                    $equipment->getDeliveredSum(),
                    $equipment->getContractedCount(),
                    $equipment->getContractedSum(),
                    $equipment->getPurchaseCount(),
                    $equipment->getPurchaseSum(),
                    $equipment->getPurchaseCount(),
                    $equipment->getPlanSum(),
                    $equipment->getProvide(),
                    $equipment->getManufacturec(),
                    $equipment->getMark(),
                    $equipment->getModel(),
                    $equipment->getOkpd2(),
                ];

                $sheet->fromArray($row, null, 'A'.$rowCount, true);
                $rowCount++;
                $index++;
            }

        }





        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size'  => 14,
                'name'  => 'Times New Roman'
            ]
        ];
        $end_cell = $sheet->getHighestRow();
        $rangeTotal = 'A5:O'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:O')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:O')->getAlignment()->setVertical('center');




        // Запись файла
        $writer = new Xlsx($spreadsheet);

//        if($save)
//        {
//            $fileName = $fileName.'_'.uniqid().'.xlsx';
//            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
//                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
//            }
//
//            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);
//
//            return $fileName;
//        }
//        else{
            $fileName = 'Свод_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
//        }

    }


}