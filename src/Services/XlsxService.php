<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;

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
        $sheet->setCellValue('C7', $userInfo->getDeclaredIndustry());
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
            $index++;
        }



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
}