<?php

namespace App\Services\excel;

use App\Entity\ProcurementProcedures;
use App\Entity\PurchasesDump;
use App\Entity\ResponsibleContactType;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\contactEmployersType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ContactAllPurchasesXlsxService extends AbstractController
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function tableGeneratorWithFactFundsBas($year, $role, $save=false)
    {
        $today = new \DateTime('now');
        $sheet_template = $this->getParameter('procurement_procedures_table_bas_directory');
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $users = $this->em->getRepository(User::class)->findByYearAndRole($year, $role);
        //change it
        $sheet = $spreadsheet->getActiveSheet();
        // Общая информация

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
        foreach ($users as $user)
        {

            $userInfo = $user->getUserInfo();
            $procedures = $this->em->getRepository(ProcurementProcedures::class)->findByUser($user);
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
                $sheet->setCellValue('Y'.$row , $userInfo->getRfSubject()->getName());
                $sheet->setCellValue('Z'.$row , $status);
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