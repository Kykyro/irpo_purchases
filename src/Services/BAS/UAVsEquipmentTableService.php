<?php

namespace App\Services\BAS;

use App\Entity\ClusterAddresses;
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

class UAVsEquipmentTableService extends AbstractController
{

    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function downloadTable($user)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('uvas_sertificate_table_directory');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $today = new \DateTime('now');
        $index = 1;
        $equipments = $user->getUAVsTypeEquipment();
        $userInfo = $user->getUserInfo();

        $sheet->setCellValue('A2',
            "Справка об оснащении специализированных классов (кружков) и центров практической подготовки БПЛА в ".
            $userInfo->getRfSubject()->getName());
        $rowCount = 5;
        foreach ($equipments as $equipment)
        {
            $row = [
                $index,
                $equipment->getName(),
                $equipment->getCategory(),
                $equipment->getPlanCount(),
                $equipment->getPlanSum(),
                $equipment->getPurchaseCount(),
                $equipment->getPurchaseSum(),
                $equipment->getContractedCount(),
                $equipment->getContractedSum(),
                $equipment->getDeliveredCount(),
                $equipment->getDeliveredSum(),
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
        $rangeTotal = 'A5:P'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle('P5:AE5')->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('P5:AE5')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:AE')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:AE')->getAlignment()->setVertical('center');

        $resultRow = [
            'Итого:',
            '=SUM(D5:D'.($end_cell).")",
            '=SUM(E5:E'.($end_cell).")",
            '=SUM(F5:F'.($end_cell).")",
            '=SUM(G5:G'.($end_cell).")",
            '=SUM(H5:H'.($end_cell).")",
            '=SUM(I5:I'.($end_cell).")",
            '=SUM(J5:J'.($end_cell).")",
            '=SUM(K5:K'.($end_cell).")",
        ];
        $sheet->fromArray($resultRow, null, 'C'.($end_cell + 1), true);

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
        $sheet_template = $this->getParameter('uvas_sertificate_table_directory');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $today = new \DateTime('now');
        $index = 1;

        $sheet->setCellValue('A2',
            "Справка об оснащении специализированных классов (кружков) и центров практической подготовки БПЛА");
        $rowCount = 5;

        $users = $this->em->getRepository(User::class)->findByYearAndRole($year, $role);
        $resultRows = [];
        $styleFill = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'b4c7dc')
            )
        );
        $styleFill2 = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'c9daf8')
            )
        );
        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            $equipments = $user->getUAVsTypeEquipment();
            $sheet->fromArray([$userInfo->getRfSubject()->getName()], null, 'A'.$rowCount, true);
            $sheet->mergeCells("A$rowCount:P$rowCount");
            $sheet->getStyle('A'.$rowCount)->applyFromArray($styleFill);
            $rowCount++;
            $index=1;
            foreach ($equipments as $equipment)
            {
                $row = [
                    $index,
                    $equipment->getName(),
                    $equipment->getCategory(),
                    $equipment->getPlanCount(),
                    $equipment->getPlanSum(),
                    $equipment->getPurchaseCount(),
                    $equipment->getPurchaseSum(),
                    $equipment->getContractedCount(),
                    $equipment->getContractedSum(),
                    $equipment->getDeliveredCount(),
                    $equipment->getDeliveredSum(),
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
            $row = [
                '',
                '',
                'Итого:',
                '=SUM(D'.($rowCount-count($equipments)).':D'.($rowCount-1).')',
                '=SUM(E'.($rowCount-count($equipments)).':E'.($rowCount-1).')',
                '=SUM(F'.($rowCount-count($equipments)).':F'.($rowCount-1).')',
                '=SUM(G'.($rowCount-count($equipments)).':G'.($rowCount-1).')',
                '=SUM(H'.($rowCount-count($equipments)).':H'.($rowCount-1).')',
                '=SUM(I'.($rowCount-count($equipments)).':I'.($rowCount-1).')',
                '=SUM(J'.($rowCount-count($equipments)).':J'.($rowCount-1).')',
                '=SUM(K'.($rowCount-count($equipments)).':K'.($rowCount-1).')',
            ];
            array_push($resultRows, $rowCount);
            $sheet->fromArray($row, null, 'A'.$rowCount, true);
            $rowCount++;

        }



        $row = [
            '',
            'Сумма:',
            '=',
            '=',
            '=',
            '=',
            '=',
            '=',
            '=',
            '=',

        ];
        foreach ($resultRows as $i){
            $row[2] = $row[2]."C$i+";
            $row[3] = $row[3]."D$i+";
            $row[4] = $row[4]."E$i+";
            $row[5] = $row[5]."F$i+";
            $row[6] = $row[6]."G$i+";
            $row[7] = $row[7]."H$i+";
            $row[8] = $row[8]."I$i+";
            $row[9] = $row[9]."J$i+";
        }
        $row[2] = substr($row[2], 0, -1);
        $row[3] = substr($row[3], 0, -1);
        $row[4] = substr($row[4], 0, -1);
        $row[5] = substr($row[5], 0, -1);
        $row[6] = substr($row[6], 0, -1);
        $row[7] = substr($row[7], 0, -1);
        $row[8] = substr($row[8], 0, -1);
        $row[9] = substr($row[9], 0, -1);
        $sheet->fromArray($row, null, 'A'.$rowCount, true);
        $rowCount++;



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
        $rangeTotal = 'A5:P'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:P')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:P')->getAlignment()->setVertical('center');
        $sheet->removeColumn('Q', 15);

        // СВОД КАТЕГОРИЙ
        $title = 'СВОД КАТЕГОРИЙ';
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $title);
        $sheet = $spreadsheet->addSheet($myWorkSheet);

        $row = [
            'Регион',
            'План',
            '',
            '',
            '',
            'В закупочных процедурах (объявлена закупка)',
            '',
            '',
            '',
            'Законтрактовано (подписан контракт, оборудование в поставке)',
            '',
            '',
            '',
            'Поставлено в ОО',
            '',
            '',
        ];
        $sheet->fromArray($row, null, 'A1', true);
        $sheet->mergeCells("A1:A2");
        $sheet->mergeCells("B1:D1");
        $sheet->mergeCells("F1:H1");
        $sheet->mergeCells("J1:L1");
        $sheet->mergeCells("N1:P1");
        $row = [
            '',
            'Мультиротор легкий',
            'Самолет легкий',
            'Образовательные БАС',
            '',
            'Мультиротор легкий',
            'Самолет легкий',
            'Образовательные БАС',
            '',
            'Мультиротор легкий',
            'Самолет легкий',
            'Образовательные БАС',
            '',
            'Мультиротор легкий',
            'Самолет легкий',
            'Образовательные БАС',
        ];
        $sheet->fromArray($row, null, 'A2', true);

        foreach ($users as $user)
        {
            $end_cell = $sheet->getHighestRow()+1;
            $userInfo = $user->getUserInfo();
            $rfSubject = $userInfo->getRfSubject();
            $row = [
                $rfSubject->getName(),
            ];

            $sheet->fromArray($row, null, 'A'.$end_cell, true);
            $sheet->fromArray($this->GetEqSum($user), null, 'B'.$end_cell, true);
        }

        $end_cell = $sheet->getHighestRow()+1;
        $row = [
            "Итог:",
            "=SUM(B3:B$end_cell)",
            "=SUM(C3:C$end_cell)",
            "=SUM(D3:D$end_cell)",
            "",
            "=SUM(F3:F$end_cell)",
            "=SUM(G3:G$end_cell)",
            "=SUM(H3:H$end_cell)",
            "",
            "=SUM(J3:J$end_cell)",
            "=SUM(K3:K$end_cell)",
            "=SUM(L3:L$end_cell)",
            "",
            "=SUM(N3:N$end_cell)",
            "=SUM(O3:O$end_cell)",
            "=SUM(P3:P$end_cell)",
        ];
        $sheet->fromArray($row, null, 'A'.$end_cell, true);

        $end_cell = $sheet->getHighestRow();
        $rangeTotal = 'A1:P'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:P')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:P')->getAlignment()->setVertical('center');

        $dimension = 50;
        $sheet->getColumnDimension('A')->setWidth($dimension);
        $dimension = 15;
        $sheet->getColumnDimension('B')->setWidth($dimension);
        $sheet->getColumnDimension('C')->setWidth($dimension);
        $sheet->getColumnDimension('D')->setWidth($dimension);
        $sheet->getColumnDimension('F')->setWidth($dimension);
        $sheet->getColumnDimension('G')->setWidth($dimension);
        $sheet->getColumnDimension('H')->setWidth($dimension);
        $sheet->getColumnDimension('J')->setWidth($dimension);
        $sheet->getColumnDimension('K')->setWidth($dimension);
        $sheet->getColumnDimension('L')->setWidth($dimension);
        $sheet->getColumnDimension('N')->setWidth($dimension);
        $sheet->getColumnDimension('O')->setWidth($dimension);
        $sheet->getColumnDimension('P')->setWidth($dimension);
        $sheet->getStyle("E3:E$end_cell")->applyFromArray($styleFill);
        $sheet->getStyle("I3:I$end_cell")->applyFromArray($styleFill);
        $sheet->getStyle("M3:M$end_cell")->applyFromArray($styleFill);
        $sheet->getStyle("A1:P2")->applyFromArray($styleFill2);
        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Свод_'.$today->format('d-m-Y').'.xlsx';
        $fileName = $fileName.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);


    }


    private function GetEqSum($user)
    {
        $row = [
            0,
            0,
            0,
            '',
            0,
            0,
            0,
            '',
            0,
            0,
            0,
            '',
            0,
            0,
            0,
        ];

        $equipments = $user->getUAVsTypeEquipment();
        foreach ($equipments as $equipment)
        {
            switch ($equipment->getCategory())
            {
                case 'Мультиротор легкий':
                    $row[0] += $equipment->getPlanCount();
                    $row[4] += $equipment->getPurchaseCount();
                    $row[8] += $equipment->getContractedCount();
                    $row[12] += $equipment->getDeliveredCount();
                    break;
                case 'Самолет легкий':
                    $row[1] += $equipment->getPlanCount();
                    $row[5] += $equipment->getPurchaseCount();
                    $row[9] += $equipment->getContractedCount();
                    $row[13] += $equipment->getDeliveredCount();
                    break;
                case 'Образовательные БАС':
                    $row[2] += $equipment->getPlanCount();
                    $row[6] += $equipment->getPurchaseCount();
                    $row[10] += $equipment->getContractedCount();
                    $row[14] += $equipment->getDeliveredCount();
                    break;
            }
        }


        return $row;
    }


}