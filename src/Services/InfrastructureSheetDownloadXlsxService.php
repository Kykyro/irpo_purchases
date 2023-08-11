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

class InfrastructureSheetDownloadXlsxService extends AbstractController
{
    public function getClusters($year)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        return $clusters = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('uf.year = :year')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%ROLE_REGION%")
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }

    public function generate($year)
    {
        $sheet_template = "../public/excel/full_IS_table.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);

        //change it
        $sheet = $spreadsheet->getActiveSheet();

        $clusters = $this->getClusters($year);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size'  => 12,
                'name'  => 'Times New Roman'
            ],

        ];

        foreach ($clusters as $cluster)
        {
            $adresses = $cluster->getClusterAddresses();
            foreach ($adresses as $addres)
            {
                $zones = $addres->getClusterZones();
                foreach ($zones as $zone)
                {
                    $infrastructureSheets = $zone->getZoneInfrastructureSheets();
                    foreach ($infrastructureSheets as $_sheet)
                    {
                        $row_array = [
                          $cluster->getUserInfo()->getEducationalOrganization(),
                            $_sheet->getName(),
                            $_sheet->getModel(),
                            '',
                            $_sheet->getType(),
                            $_sheet->getTotalNumber(),
                            '',
                            '',
                            '',
                            $_sheet->getCountryOfOrigin(),
                            $_sheet->getOKPD2(),
                            $_sheet->getKTRU(),
                            $_sheet->getComment(),
                            $cluster->getUserInfo()->getDeclaredIndustry(),

                        ];
                        $row = $sheet->getHighestRow()+1;
                        $sheet->fromArray($row_array, '', "A$row");
                        $sheet->getRowDimension($row)->setRowHeight(65);
                    }
                }
            }
        }
        $last_row = $sheet->getHighestRow()+1;
        $rangeTotal = 'A2:N'.$last_row;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle('A:N')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:N')->getAlignment()->setVertical('center');
        $sheet->getStyle('A:N')->getAlignment()->setWrapText(true);

        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Инфраструткурный лист.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

}