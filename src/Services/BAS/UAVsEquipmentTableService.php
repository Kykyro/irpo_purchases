<?php

namespace App\Services\BAS;

use App\Entity\ClusterAddresses;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchasesDump;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UAVsEquipmentTableService extends AbstractController
{



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
        $rangeTotal = 'A5:J'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Q')->getAlignment()->setVertical('center');




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