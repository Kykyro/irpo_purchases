<?php

namespace App\Services;

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

class XlsxService extends AbstractController
{
    public function generate()
    {
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Содержимое ячейки А1');
        $sheet->setTitle("Это новый лист документа");

        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'excel_symfony4.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function generatePurchasesProcedure(int $user_id, int $year)
    {
        $procedures = $this->getProcedure($user_id, $year);
        $sheet_template = "../public/excel/Закупочные процедуры шаблон.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $userInfo = $procedures[0]->getUser()->getUserInfo();
        //change it
        $sheet = $spreadsheet->getActiveSheet();
        // Общая информация
        $sheet->setCellValue('C4', $userInfo->getRfSubject()->getName());
        $sheet->setCellValue('C5', $userInfo->getOrganization());
        $sheet->setCellValue('C6', $userInfo->getEducationalOrganization());
        $sheet->setCellValue('C7', $userInfo->getCluster());
        $sheet->setCellValue('C8', $userInfo->getDeclaredIndustry());


        $styleFill = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        );

        $initialFedFundSUM = "=";
        $initialSubFundSUM = "=";
        $initialEmpFundSUM = "=";
        $initialOrgFundSUM = "=";

        $finFedFundSUM = "=";
        $finSubFundSUM = "=";
        $finEmpFundSUM = "=";
        $finOrgFundSUM = "=";

        // Инфо о процедурах
        $index = 1;
        foreach ($procedures as &$val) {
            $row = $sheet->getHighestRow()+1;

            // формулы суммирования
            $initialSUMRANGE = 'E'.$row.':H'.$row;
            $finSUMRANGE = 'U'.$row.':X'.$row;



            $sheet->setCellValue('D'.$row , "=SUM($initialSUMRANGE)");
            $sheet->setCellValue('T'.$row , "=SUM($finSUMRANGE)");


            // запись строк
            $sheet->setCellValue('A'.$row, $index);
            $sheet->fromArray($val->getAsRow(), null, 'B'.$row);

            if($sheet->getCell('P'.$row) == "")
            {
                foreach ($sheet->rangeToArray($initialSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != ""){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill);
                            switch ($cell) {
                                case "E":
                                    $initialFedFundSUM = $initialFedFundSUM.$cellCoordinates."+";
                                    break;
                                case  "F":
                                    $initialSubFundSUM = $initialSubFundSUM.$cellCoordinates."+";
                                    break;
                                case "G":
                                    $initialEmpFundSUM = $initialEmpFundSUM.$cellCoordinates."+";
                                    break;
                                case "H":
                                    $initialOrgFundSUM = $initialOrgFundSUM.$cellCoordinates."+";
                                    break;

                            }

                        }
                    }
                }
            }
            else{
                foreach ($sheet->rangeToArray($finSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != ""){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill);
                        }
                        switch ($cell) {
                            case "U":
                                $finFedFundSUM = $finFedFundSUM.$cellCoordinates."+";
                                break;
                            case  "V":
                                $finSubFundSUM = $finSubFundSUM.$cellCoordinates."+";
                                break;
                            case "W":
                                $finEmpFundSUM = $finEmpFundSUM.$cellCoordinates."+";
                                break;
                            case "X":
                                $finOrgFundSUM = $finOrgFundSUM.$cellCoordinates."+";
                                break;

                        }
                    }
                }
            }

            $index++;
        }
        $initialFedFundSUM = substr($initialFedFundSUM,0,-1);
        $initialSubFundSUM = substr($initialSubFundSUM,0,-1);
        $initialEmpFundSUM = substr($initialEmpFundSUM,0,-1);
        $initialOrgFundSUM = substr($initialOrgFundSUM,0,-1);

        $finFedFundSUM = substr($finFedFundSUM,0,-1);
        $finSubFundSUM = substr($finSubFundSUM,0,-1);
        $finEmpFundSUM = substr($finEmpFundSUM,0,-1);
        $finOrgFundSUM = substr($finOrgFundSUM,0,-1);


        // стили
        $styleArray = [ 'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];

        $end_cell = 14 + $index-1;
        $rangeTotal = 'A14:Z'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        // перенос строк
        $sheet->getStyle($rangeTotal)
            ->getAlignment()
            ->setWrapText(true);
        // толстый текст
        $sheet->getStyle($rangeTotal)
            ->getFont()
            ->setBold( false );

        // Нижняя строка
        $sheet->setCellValue('C'.$end_cell, 'Итого:');
        $sheet->setCellValue('S'.$end_cell, 'Итого:');
        $initialSumCell = 'D'.$end_cell;
        $initialSumFormulaRange = 'D14:D'.($end_cell-1);
        $finSumCell = 'T'.$end_cell;
        $finSumFormulaRange = 'T14:T'.($end_cell-1);
        $sheet->setCellValue($initialSumCell , "=SUM($initialSumFormulaRange)");
        $sheet->setCellValue($finSumCell , "=SUM($finSumFormulaRange)");

        $sheet->setCellValue('E'.$end_cell, $initialFedFundSUM);
        $sheet->setCellValue('F'.$end_cell, $initialSubFundSUM);
        $sheet->setCellValue('G'.$end_cell, $initialEmpFundSUM);
        $sheet->setCellValue('H'.$end_cell, $initialOrgFundSUM);

        $sheet->setCellValue('U'.$end_cell, $finFedFundSUM);
        $sheet->setCellValue('V'.$end_cell, $finSubFundSUM);
        $sheet->setCellValue('W'.$end_cell, $finEmpFundSUM);
        $sheet->setCellValue('X'.$end_cell, $finOrgFundSUM);

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);

        $fileName = $userInfo->getRfSubject()->getName().'_'.$year.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);


    }

    public function getProcedure(int $user_id, int $year)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->find(User::class, $user_id);
        $user_info = $user->getUserInfo();

        $repository = $this->getDoctrine()
            ->getRepository(ProcurementProcedures::class);


        $prodProc = $repository->findByUserAndYear($user, $year);


        return $prodProc;
    }



    public function save()
    {
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Содержимое ячейки А1');
        $sheet->setTitle("Это новый лист документа");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
        // e.g /var/www/project/public/excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . '/excel_symfony4.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        // Return a text response to the browser saying that the excel was succesfully created
        return new Response("Excel generated succesfully");
    }

    public function getProcedureById(int $user_id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $prodProc = $entity_manager->getRepository(ProcurementProcedures::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->andWhere('u.id = :id')
            ->andWhere('a.isDeleted = :isDeleted')
            ->setParameter('id', "$user_id")
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();


        return $prodProc;
    }
    public function getUserById($id){
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);
        return $user;

    }
    public function generatePurchasesProcedureTable(int $user_id)
    {
        $procedures = $this->getProcedureById($user_id);
        $today = new \DateTime('now');
        $user = $this->getUserById($user_id);
        $userInfo = $user->getUserInfo();

        return $this->tableGeneratorWithFactFunds($userInfo, $procedures, $today);


    }

    public function generatePurchasesProcedureByDump(int $dump_id, SerializerInterface $serializer){
        $entity_manager = $this->getDoctrine()->getManager();
        $dump = $entity_manager->getRepository(PurchasesDump::class)->find($dump_id);
        $dumpData = $dump->getDump();
        $dumpProcedures = $serializer->deserialize($dumpData->getDump(), 'App\Entity\ProcurementProcedures[]' , 'json');
        $procedures = [];
        foreach ($dumpProcedures as $p)
        {
            if(!$p->getIsDeleted())
            {
                array_push($procedures, $p);
            }
        }
        $today = $dump->getCreatedAt()->setTime(0,0,0,0);
        $userInfo = $dump->getUser()->getUserInfo();

        return $this->tableGeneratorWithFactFunds($userInfo, $procedures, $today);
    }

    public function tableGenerator($userInfo, $procedures, $today)
    {
        $sheet_template = "../public/excel/Закупочные процедуры шаблон.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);

        //change it
        $sheet = $spreadsheet->getActiveSheet();
        // Общая информация
        $sheet->setCellValue('C4', $userInfo->getRfSubject()->getName());
        $sheet->setCellValue('C5', $userInfo->getOrganization());
        $sheet->setCellValue('C6', $userInfo->getEducationalOrganization());
        $sheet->setCellValue('C7', $userInfo->getCluster());
        $sheet->setCellValue('C8', $userInfo->getDeclaredIndustry());


        $styleFill = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        );

        $initialFedFundSUM = "=";
        $initialSubFundSUM = "=";
        $initialEmpFundSUM = "=";
        $initialOrgFundSUM = "=";

        $finFedFundSUM = "=";
        $finSubFundSUM = "=";
        $finEmpFundSUM = "=";
        $finOrgFundSUM = "=";

        // Инфо о процедурах
        $index = 1;
        foreach ($procedures as &$val) {
            $row = $sheet->getHighestRow()+1;
            $date = $val->getDateOfConclusion();

            $isNotComplite = false;
            if(is_null($date) or $date >= $today){

                $isNotComplite = true;
            }
            // формулы суммирования
            $initialSUMRANGE = 'E'.$row.':H'.$row;
            $finSUMRANGE = 'U'.$row.':X'.$row;



            $sheet->setCellValue('D'.$row , "=SUM($initialSUMRANGE)");
            $sheet->setCellValue('T'.$row , "=SUM($finSUMRANGE)");
            $row_arr = ['E', 'F', 'G', 'H', 'U', 'V', 'W', 'X', 'D', 'T'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$row)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }


            // запись строк
            $sheet->setCellValue('A'.$row, $index);
            $sheet->fromArray($val->getAsRow(), null, 'B'.$row);

            if($isNotComplite)
            {
                foreach ($sheet->rangeToArray($initialSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != ""){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill);

                            switch ($cell) {
                                case "E":
                                    $initialFedFundSUM = $initialFedFundSUM.$cellCoordinates."+";
                                    break;
                                case  "F":
                                    $initialSubFundSUM = $initialSubFundSUM.$cellCoordinates."+";
                                    break;
                                case "G":
                                    $initialEmpFundSUM = $initialEmpFundSUM.$cellCoordinates."+";
                                    break;
                                case "H":
                                    $initialOrgFundSUM = $initialOrgFundSUM.$cellCoordinates."+";
                                    break;

                            }

                        }
                    }
                }
            }
            else{
                foreach ($sheet->rangeToArray($finSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != ""){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill);
                        }

                        switch ($cell) {
                            case "U":
                                $finFedFundSUM = $finFedFundSUM.$cellCoordinates."+";
                                break;
                            case  "V":
                                $finSubFundSUM = $finSubFundSUM.$cellCoordinates."+";
                                break;
                            case "W":
                                $finEmpFundSUM = $finEmpFundSUM.$cellCoordinates."+";
                                break;
                            case "X":
                                $finOrgFundSUM = $finOrgFundSUM.$cellCoordinates."+";
                                break;

                        }
                    }
                }
            }

            $index++;
        }
        $initialFedFundSUM = substr($initialFedFundSUM,0,-1);
        $initialSubFundSUM = substr($initialSubFundSUM,0,-1);
        $initialEmpFundSUM = substr($initialEmpFundSUM,0,-1);
        $initialOrgFundSUM = substr($initialOrgFundSUM,0,-1);

        $finFedFundSUM = substr($finFedFundSUM,0,-1);
        $finSubFundSUM = substr($finSubFundSUM,0,-1);
        $finEmpFundSUM = substr($finEmpFundSUM,0,-1);
        $finOrgFundSUM = substr($finOrgFundSUM,0,-1);

        // стили
        $styleArray = [ 'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
        ];

        $end_cell = 14 + $index-1;
        $rangeTotal = 'A14:Z'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        // перенос строк
        $sheet->getStyle($rangeTotal)
            ->getAlignment()
            ->setWrapText(true);
        // толстый текст
        $sheet->getStyle($rangeTotal)
            ->getFont()
            ->setBold( false );

        // Нижняя строка
        $sheet->setCellValue('C'.$end_cell, 'Итого:');
        $sheet->setCellValue('S'.$end_cell, 'Итого:');
        $initialSumCell = 'D'.$end_cell;
        $initialSumFormulaRange = 'D14:D'.($end_cell-1);
        $finSumCell = 'T'.$end_cell;
        $finSumFormulaRange = 'T14:T'.($end_cell-1);
        $sheet->setCellValue($initialSumCell , "=SUM($initialSumFormulaRange)");
        $sheet->setCellValue($finSumCell , "=SUM($finSumFormulaRange)");

        $sheet->setCellValue('E'.$end_cell, $initialFedFundSUM);
        $sheet->setCellValue('F'.$end_cell, $initialSubFundSUM);
        $sheet->setCellValue('G'.$end_cell, $initialEmpFundSUM);
        $sheet->setCellValue('H'.$end_cell, $initialOrgFundSUM);

        $sheet->setCellValue('U'.$end_cell, $finFedFundSUM);
        $sheet->setCellValue('V'.$end_cell, $finSubFundSUM);
        $sheet->setCellValue('W'.$end_cell, $finEmpFundSUM);
        $sheet->setCellValue('X'.$end_cell, $finOrgFundSUM);


        $row_arr = ['E', 'F', 'G', 'H', 'U', 'V', 'W', 'X', 'D', 'T'];
        foreach ($row_arr as $j){
            $sheet->getStyle($j.$end_cell)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        }

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);

        $fileName = $userInfo->getRfSubject()->getName().'_'.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
    public function tableGeneratorWithFactFunds($userInfo, $procedures, $today)
    {
        $sheet_template = "../public/excel/ProcurementProceduresTable_v2.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);

        //change it
        $sheet = $spreadsheet->getActiveSheet();
        // Общая информация
        $sheet->setCellValue('C4', $userInfo->getRfSubject()->getName());
        $sheet->setCellValue('C5', $userInfo->getOrganization());
        $sheet->setCellValue('C6', $userInfo->getEducationalOrganization());
        $sheet->setCellValue('C7', $userInfo->getCluster());
        $sheet->setCellValue('C8', $userInfo->getDeclaredIndustry());


        $styleFill = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        );
        $styleFill2 = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'afd095')
            )
        );

        $initialFedFundSUM = "=";
        $initialSubFundSUM = "=";
        $initialEmpFundSUM = "=";
        $initialOrgFundSUM = "=";

        $finFedFundSUM = "=";
        $finSubFundSUM = "=";
        $finEmpFundSUM = "=";
        $finOrgFundSUM = "=";

        // Инфо о процедурах
        $index = 1;
        foreach ($procedures as &$val) {
            $row = $sheet->getHighestRow()+1;
            $dateTime = new \DateTime("@{$today->getTimeStamp()}");
            $status = $val->getPurchasesStatus($dateTime);

            // формулы суммирования
            $initialSUMRANGE = 'E'.$row.':H'.$row;
            $finSUMRANGE = 'U'.$row.':X'.$row;

            $row_arr = ['E', 'F', 'G', 'H', 'U', 'V', 'W', 'X', 'D', 'T', 'Z', 'AA', 'AB', 'AC'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$row)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }

            // запись строк
            $sheet->setCellValue('A'.$row, $index);
            $sheet->fromArray($val->getAsRowWithFactFunds(), null, 'B'.$row);
//            $sheet->setCellValue('D'.$row , $status);
            if($status == 'announced')
            {
                foreach ($sheet->rangeToArray($initialSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != "" and (float)$sheet->getCell($cellCoordinates)->getValue() != 0){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill);

                            switch ($cell) {
                                case "E":
                                    $initialFedFundSUM = $initialFedFundSUM.$cellCoordinates."+";
                                    break;
                                case  "F":
                                    $initialSubFundSUM = $initialSubFundSUM.$cellCoordinates."+";
                                    break;
                                case "G":
                                    $initialEmpFundSUM = $initialEmpFundSUM.$cellCoordinates."+";
                                    break;
                                case "H":
                                    $initialOrgFundSUM = $initialOrgFundSUM.$cellCoordinates."+";
                                    break;

                            }

                        }
                    }
                }
            }
            elseif($status == 'contract'){
                foreach ($sheet->rangeToArray($finSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != "" and (float)$sheet->getCell($cellCoordinates)->getValue() != 0){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill2);
                        }

                        switch ($cell) {
                            case "U":
                                $finFedFundSUM = $finFedFundSUM.$cellCoordinates."+";
                                break;
                            case  "V":
                                $finSubFundSUM = $finSubFundSUM.$cellCoordinates."+";
                                break;
                            case "W":
                                $finEmpFundSUM = $finEmpFundSUM.$cellCoordinates."+";
                                break;
                            case "X":
                                $finOrgFundSUM = $finOrgFundSUM.$cellCoordinates."+";
                                break;

                        }
                    }
                }
            }

            $index++;
        }
        $initialFedFundSUM = substr($initialFedFundSUM,0,-1);
        $initialSubFundSUM = substr($initialSubFundSUM,0,-1);
        $initialEmpFundSUM = substr($initialEmpFundSUM,0,-1);
        $initialOrgFundSUM = substr($initialOrgFundSUM,0,-1);

        $finFedFundSUM = substr($finFedFundSUM,0,-1);
        $finSubFundSUM = substr($finSubFundSUM,0,-1);
        $finEmpFundSUM = substr($finEmpFundSUM,0,-1);
        $finOrgFundSUM = substr($finOrgFundSUM,0,-1);

        // стили
        $styleArray = [ 'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
        ];

        $end_cell = 14 + $index-1;
        $rangeTotal = 'A14:AD'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        // перенос строк
        $sheet->getStyle($rangeTotal)
            ->getAlignment()
            ->setWrapText(true);
        // толстый текст
        $sheet->getStyle($rangeTotal)
            ->getFont()
            ->setBold( false );

        // Нижняя строка
        $sheet->setCellValue('C'.$end_cell, 'Итого:');
        $sheet->setCellValue('S'.$end_cell, 'Итого:');
        $initialSumCell = 'D'.$end_cell;
        $initialSumFormulaRange = 'D14:D'.($end_cell-1);
        $finSumCell = 'T'.$end_cell;
        $finSumFormulaRange = 'T14:T'.($end_cell-1);
        $sheet->setCellValue($initialSumCell , "=SUM($initialSumFormulaRange)");
        $sheet->setCellValue($finSumCell , "=SUM($finSumFormulaRange)");
        $rowSUM = $end_cell-1;

        $sheet->setCellValue('Z'.$end_cell, "=SUM(Z14:Z$rowSUM)");
        $sheet->setCellValue('AA'.$end_cell, "=SUM(AA14:AA$rowSUM)");
        $sheet->setCellValue('AB'.$end_cell, "=SUM(AB14:AB$rowSUM)");
        $sheet->setCellValue('AC'.$end_cell, "=SUM(AC14:AC$rowSUM)");

        $sheet->setCellValue('E'.$end_cell, $initialFedFundSUM);
        $sheet->setCellValue('F'.$end_cell, $initialSubFundSUM);
        $sheet->setCellValue('G'.$end_cell, $initialEmpFundSUM);
        $sheet->setCellValue('H'.$end_cell, $initialOrgFundSUM);

        $sheet->setCellValue('U'.$end_cell, $finFedFundSUM);
        $sheet->setCellValue('V'.$end_cell, $finSubFundSUM);
        $sheet->setCellValue('W'.$end_cell, $finEmpFundSUM);
        $sheet->setCellValue('X'.$end_cell, $finOrgFundSUM);


        $row_arr = ['E', 'F', 'G', 'H', 'U', 'V', 'W', 'X', 'D', 'T', 'Z', 'AA', 'AB', 'AC'];
        foreach ($row_arr as $j){
            $sheet->getStyle($j.$end_cell)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        }

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);

        $fileName = $userInfo->getRfSubject()->getName().'_'.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}