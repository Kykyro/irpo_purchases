<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\PurchasesDump;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ClusterRequestCertificateService extends AbstractController
{
    public function parse($filename)
    {
        /** @var Spreadsheet $spreadsheet */
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($this->getParameter('cluster_request_table_directory')."/$filename");
        $data = [];
        /** @var Worksheet $sheet */
        try{
            $sheet = $spreadsheet->getSheetByName('Все заявки');
        }
        catch (Exception $e)
        {
            return $data;
        }
        $endCell = $sheet->getHighestRow();
        $data = $sheet->rangeToArray('A2:H'.$endCell);

        return $data;
    }
    public function download($filename, $rows)
    {
        $data = $this->parse($filename);

        $today = new \DateTime('now');


        $templateProcessor = new TemplateProcessor($this->getParameter('cluster_request_certificate'));
        $replacements = [];

        foreach ($rows as $row)
        {
            array_push($replacements, $data[$row]);
        }
        $templateProcessor->cloneBlock('block', count($replacements), true, true);
        $count = 1;
        foreach ($replacements as $replacement)
        {
            $templateProcessor->setValues(
                [
                    'count#'.$count => "$count) ",
                    'industry#'.$count => $replacement[3],
                    'base#'.$count => $replacement[5],
                    'status#'.$count => $replacement[6],
                    'reason#'.$count => $replacement[7],
                ]
            );

            $count++;
        }

        $fileName = 'Справка по заявкам '.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);



    }


}