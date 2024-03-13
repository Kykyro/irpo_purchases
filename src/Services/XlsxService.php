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
    public function generatePurchasesProcedureTable(int $user_id, $save=false)
    {
        $procedures = $this->getProcedureById($user_id);
        $today = new \DateTime('now');
        $user = $this->getUserById($user_id);
        $userInfo = $user->getUserInfo();

        return $this->tableGeneratorWithFactFunds($userInfo, $procedures, $today, $save);


    }
    public function generatePurchasesProcedureTableBas(int $user_id, $save=false)
    {
        $procedures = $this->getProcedureById($user_id);
        $today = new \DateTime('now');
        $user = $this->getUserById($user_id);
        $userInfo = $user->getUserInfo();

        return $this->tableGeneratorWithFactFundsBas($userInfo, $procedures, $today, $save);


    }
    public function generatePurchasesProcedureTableWithDate(int $user_id, \DateTime $today)
    {
        $procedures = $this->getProcedureById($user_id);
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
            $sheet->fromArray($val->getAsRow(), '', 'B'.$row);

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

    public function tableGeneratorWithFactFunds($userInfo, $procedures, $today, $save=false)
    {
        $sheet_template = $this->getParameter('procurement_procedures_table_directory');
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
        $styleFill3 = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'ffd0a5')
            )
        );
        $styleFillCancelled = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'ca8270')
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

        $factFedFundSUM = "=";
        $factSubFundSUM = "=";
        $factEmpFundSUM = "=";
        $factOrgFundSUM = "=";

        // Инфо о процедурах
        $index = 1;
        foreach ($procedures as &$val) {
            $row = $sheet->getHighestRow()+1;
            $dateTime = new \DateTime("@{$today->getTimeStamp()}");
            $status = $val->getPurchasesStatus($dateTime);

            // формулы суммирования
            $initialSUMRANGE = 'E'.$row.':H'.$row;
            $finSUMRANGE = 'U'.$row.':X'.$row;
            $factSUMRANGE = 'Z'.$row.':AC'.$row;

            $row_arr = ['E', 'F', 'G', 'H', 'U', 'V', 'W', 'X', 'D', 'T', 'Z', 'AA', 'AB', 'AC'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$row)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }

            // запись строк
            $sheet->setCellValue('A'.$row, $index);
            $sheet->fromArray($val->getAsRowWithFactFunds(), null, 'B'.$row);
            $sheet->setCellValue('AE'.$row , $status);
            $deliveryDate = $val->getDeliveryTime();
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
                            case "Z":
                                $factFedFundSUM = $factFedFundSUM.$cellCoordinates."+";
                                break;
                            case "AA":
                                $factSubFundSUM = $factSubFundSUM.$cellCoordinates."+";
                                break;
                            case "AB":
                                $factEmpFundSUM = $factEmpFundSUM.$cellCoordinates."+";
                                break;
                            case "AC":
                                $factOrgFundSUM = $factOrgFundSUM.$cellCoordinates."+";
                                break;
                        }
                    }
                }
//                if($deliveryDate <= $today and !is_null($deliveryDate))
                foreach ($sheet->rangeToArray($factSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != "" and (float)$sheet->getCell($cellCoordinates)->getValue() != 0){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill3);
                        }

                        switch ($cell) {
                            case "Z":
                                $factFedFundSUM = $factFedFundSUM.$cellCoordinates."+";
                                break;
                            case "AA":
                                $factSubFundSUM = $factSubFundSUM.$cellCoordinates."+";
                                break;
                            case "AB":
                                $factEmpFundSUM = $factEmpFundSUM.$cellCoordinates."+";
                                break;
                            case "AC":
                                $factOrgFundSUM = $factOrgFundSUM.$cellCoordinates."+";
                                break;
                        }
                    }
                }
            }
            elseif($status == 'cancelled')
            {
                $sheet->getStyle("A$row:AD$row")->applyFromArray($styleFillCancelled);
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

        $factFedFundSUM = substr($factFedFundSUM,0,-1);
        $factSubFundSUM = substr($factSubFundSUM,0,-1);
        $factEmpFundSUM = substr($factEmpFundSUM,0,-1);
        $factOrgFundSUM = substr($factOrgFundSUM,0,-1);

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

        $sheet->setCellValue('Z'.$end_cell, $factFedFundSUM);
        $sheet->setCellValue('AA'.$end_cell, $factSubFundSUM);
        $sheet->setCellValue('AB'.$end_cell, $factEmpFundSUM);
        $sheet->setCellValue('AC'.$end_cell, $factOrgFundSUM);

        $sheet->setCellValue('E'.$end_cell, $initialFedFundSUM);
        $sheet->setCellValue('F'.$end_cell, $initialSubFundSUM);
        $sheet->setCellValue('G'.$end_cell, $initialEmpFundSUM);
        $sheet->setCellValue('H'.$end_cell, $initialOrgFundSUM);

        $sheet->setCellValue('U'.$end_cell, $finFedFundSUM);
        $sheet->setCellValue('V'.$end_cell, $finSubFundSUM);
        $sheet->setCellValue('W'.$end_cell, $finEmpFundSUM);
        $sheet->setCellValue('X'.$end_cell, $finOrgFundSUM);


        $sheet->getStyle('E'.$end_cell.':H'.$end_cell)->applyFromArray($styleFill);
        $sheet->getStyle('U'.$end_cell.':X'.$end_cell)->applyFromArray($styleFill2);
        $sheet->getStyle('Z'.$end_cell.':AC'.$end_cell)->applyFromArray($styleFill3);

        $row_arr = ['E', 'F', 'G', 'H', 'U', 'V', 'W', 'X', 'D', 'T', 'Z', 'AA', 'AB', 'AC'];
        foreach ($row_arr as $j){
            $sheet->getStyle($j.$end_cell)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        }

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);


        if($save)
        {
            $fileName = $userInfo->getRfSubject()->getName().'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('purchases_table_directory'))) {
                mkdir($this->getParameter('purchases_table_directory'), 0777, true);
            }

            $writer->save($this->getParameter('purchases_table_directory').'/'.$fileName);

            return $fileName;
        }
        else
        {
            $fileName = $userInfo->getRfSubject()->getName().'_'.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function tableGeneratorWithFactFundsBas($userInfo, $procedures, $today, $save=false)
    {
        $sheet_template = $this->getParameter('procurement_procedures_table_bas_directory');
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);

        //change it
        $sheet = $spreadsheet->getActiveSheet();
        // Общая информация
        $sheet->setCellValue('C4', $userInfo->getRfSubject()->getName());

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
        $styleFill3 = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'ffd0a5')
            )
        );
        $styleFillCancelled = array(
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'ca8270')
            )
        );

        $initialFedFundSUM = "=";
        $initialSubFundSUM = "=";

        $finFedFundSUM = "=";
        $finSubFundSUM = "=";

        $factFedFundSUM = "=";
        $factSubFundSUM = "=";

        // Инфо о процедурах
        $index = 1;
        foreach ($procedures as &$val) {
            $row = $sheet->getHighestRow()+1;
//            dd($row);
            $dateTime = new \DateTime("@{$today->getTimeStamp()}");
            $status = $val->getPurchasesStatus($dateTime);

            // формулы суммирования
            $initialSUMRANGE = 'E'.$row.':F'.$row;
            $finSUMRANGE = 'S'.$row.':T'.$row;
            $factSUMRANGE = 'V'.$row.':W'.$row;

            $row_arr = ['E', 'F',  'S', 'T', 'V', 'W'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$row)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }

            // запись строк
            $sheet->setCellValue('A'.$row, $index);
            $sheet->fromArray($val->getAsRowForBas(), null, 'B'.$row);
            $sheet->setCellValue('AE'.$row , $status);
            $deliveryDate = $val->getDeliveryTime();
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
                            case "S":
                                $finFedFundSUM = $finFedFundSUM.$cellCoordinates."+";
                                break;
                            case  "T":
                                $finSubFundSUM = $finSubFundSUM.$cellCoordinates."+";
                                break;

                            case "V":
                                $factFedFundSUM = $factFedFundSUM.$cellCoordinates."+";
                                break;
                            case "W":
                                $factSubFundSUM = $factSubFundSUM.$cellCoordinates."+";
                                break;

                        }
                    }
                }
//                if($deliveryDate <= $today and !is_null($deliveryDate))
                foreach ($sheet->rangeToArray($factSUMRANGE, null, true, true, true ) as $_row){
                    foreach (array_keys($_row) as $cell){
                        $cellCoordinates = $cell.$row;
                        if($sheet->getCell($cellCoordinates) != "" and (float)$sheet->getCell($cellCoordinates)->getValue() != 0){
                            $sheet->getStyle($cellCoordinates)->applyFromArray($styleFill3);
                        }

                        switch ($cell) {
                            case "Z":
                                $factFedFundSUM = $factFedFundSUM.$cellCoordinates."+";
                                break;
                            case "AA":
                                $factSubFundSUM = $factSubFundSUM.$cellCoordinates."+";
                                break;

                        }
                    }
                }
            }
            elseif($status == 'cancelled')
            {
                $sheet->getStyle("A$row:X$row")->applyFromArray($styleFillCancelled);
            }


            $index++;
        }
        $initialFedFundSUM = substr($initialFedFundSUM,0,-1);
        $initialSubFundSUM = substr($initialSubFundSUM,0,-1);


        $finFedFundSUM = substr($finFedFundSUM,0,-1);
        $finSubFundSUM = substr($finSubFundSUM,0,-1);


        $factFedFundSUM = substr($factFedFundSUM,0,-1);
        $factSubFundSUM = substr($factSubFundSUM,0,-1);


        // стили
        $styleArray = [ 'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ]
        ];

        $end_cell = 11 + $index-1;
        $rangeTotal = 'A11:X'.$end_cell;
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
        $sheet->setCellValue('Q'.$end_cell, 'Итого:');
        $initialSumCell = 'D'.$end_cell;
        $initialSumFormulaRange = 'D11:D'.($end_cell-1);
        $finSumCell = 'R'.$end_cell;
        $finSumFormulaRange = 'R11:R'.($end_cell-1);
        $sheet->setCellValue($initialSumCell , "=SUM($initialSumFormulaRange)");
        $sheet->setCellValue($finSumCell , "=SUM($finSumFormulaRange)");

        $sheet->setCellValue('V'.$end_cell, $factFedFundSUM);
        $sheet->setCellValue('W'.$end_cell, $factSubFundSUM);

        $sheet->setCellValue('E'.$end_cell, $initialFedFundSUM);
        $sheet->setCellValue('F'.$end_cell, $initialSubFundSUM);

        $sheet->setCellValue('S'.$end_cell, $finFedFundSUM);
        $sheet->setCellValue('T'.$end_cell, $finSubFundSUM);


        $sheet->getStyle('E'.$end_cell.':F'.$end_cell)->applyFromArray($styleFill);
        $sheet->getStyle('S'.$end_cell.':T'.$end_cell)->applyFromArray($styleFill2);
        $sheet->getStyle('V'.$end_cell.':W'.$end_cell)->applyFromArray($styleFill3);

        $row_arr = ['E', 'F',  'S', 'T', 'V', 'W'];
        foreach ($row_arr as $j){
            $sheet->getStyle($j.$end_cell)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        }

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);


        if($save)
        {
            $fileName = $userInfo->getRfSubject()->getName().'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('purchases_table_directory'))) {
                mkdir($this->getParameter('purchases_table_directory'), 0777, true);
            }

            $writer->save($this->getParameter('purchases_table_directory').'/'.$fileName);

            return $fileName;
        }
        else
        {
            $fileName = $userInfo->getRfSubject()->getName().'_'.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
}