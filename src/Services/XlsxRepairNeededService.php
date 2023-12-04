<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\ProfEduOrg;
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

class XlsxRepairNeededService extends AbstractController
{
    public function generate($year, $user)
    {
        $em = $this->getDoctrine()->getManager();
        $sheet_template = "../public/excel/Форма_мониторинга_капремонтов_ПОО.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($year);

        $index = 4;
        $region = $user->getUserInfo()->getRfSubject();
        $edu = $em->getRepository(ProfEduOrg::class)
            ->findAllByRegionAndYear($region->getId(), $year);

        // стили
        $separateFill = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'CCCCCC')
            )
        );
        $styleArray = [ 'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
        ];

        foreach ($edu as $e){
            foreach ($e->getBuildings() as $building)
            {
                $row = [
                    $region->getName(),
                    $e->getFullName(),
                    $e->getShortName(),
                    $e->getAddress(),
                    $building->getName(),
                    $building->getBuildingCategory() ? $building->getBuildingCategory()->getName() : "",
                    $building->getAddress(),
                    $building->getArea(),
                    $building->getRepairArea(),
                    $building->getBuildingPriority() ? $building->getBuildingPriority()->getName() : "",
                    $building->getNeededFunds(),
                    $building->getPossibleFunds(),
                    $building->getAddFunds(),

                ];


                $sheet->fromArray($row, '-', "A".$index, true );
                $index++;
            }
            $sheet->fromArray([''], '-', "A".($index+1), true );
            $sheet->getStyle("A".($index).":M".($index))->applyFromArray($separateFill);
            $index++;
        }
        $sheet->getStyle("A4:M".($index-1))->applyFromArray($styleArray);
        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = $region->getName().'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
    public function generateAllByYear($year)
    {
        $em = $this->getDoctrine()->getManager();
        $sheet_template = "../public/excel/Форма_мониторинга_капремонтов_ПОО.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($year);

        $index = 4;

        $edu = $em->getRepository(ProfEduOrg::class)
            ->findAllByYear($year);

        // стили
        $separateFill = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'CCCCCC')
            )
        );
        $styleArray = [ 'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
        ];

        foreach ($edu as $e){
            foreach ($e->getBuildings() as $building)
            {
                $row = [
                    $e->getRegion()->getName(),
                    $e->getFullName(),
                    $e->getShortName(),
                    $e->getAddress(),
                    $building->getName(),
                    $building->getBuildingCategory() ? $building->getBuildingCategory()->getName() : "",
                    $building->getAddress(),
                    $building->getArea(),
                    $building->getRepairArea(),
                    $building->getBuildingPriority() ? $building->getBuildingPriority()->getName() : "",
                    $building->getNeededFunds(),
                    $building->getPossibleFunds(),
                    $building->getAddFunds(),

                ];


                $sheet->fromArray($row, '-', "A".$index, true );
                $index++;
            }
//            $sheet->fromArray([''], '-', "A".($index+1), true );
//            $sheet->getStyle("A".($index).":M".($index))->applyFromArray($separateFill);
//            $index++;
        }
        $sheet->getStyle("A4:M".($index-1))->applyFromArray($styleArray);
        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'мониторинг капремонтов ПОО ('.$year.' год).xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }


}