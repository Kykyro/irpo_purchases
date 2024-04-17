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
                $equipment->getDeliveredCount(),
                $equipment->getDeliveredSum(),
                $equipment->getContractedCount(),
                $equipment->getContractedSum(),
                $equipment->getPurchaseCount(),
                $equipment->getPurchaseSum(),
                "",
                $equipment->getPlanSum(),
                $equipment->getMark(),
                $equipment->getModel(),
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
        $rangeTotal = 'A5:L'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:L')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:L')->getAlignment()->setVertical('center');




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
        $rangeTotal = 'A5:L'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:L')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:L')->getAlignment()->setVertical('center');




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
                    "",
                    $equipment->getPlanSum(),
                    $equipment->getMark(),
                    $equipment->getModel(),
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
        $rangeTotal = 'A5:L'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:L')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:L')->getAlignment()->setVertical('center');




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