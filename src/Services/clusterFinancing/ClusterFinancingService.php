<?php

namespace App\Services\clusterFinancing;

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

class ClusterFinancingService extends AbstractController
{
    private $em;
    private $contactTypes;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->contactTypes = $this->em->getRepository(ResponsibleContactType::class)->findAll();
    }

    public function generate()
    {
        /** @var Spreadsheet $spreadsheet */
        $sheet_template = "../public/excel/Запрос_профессионалитет_Суханов_10_06.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Содержимое ячейки А1');
//        $sheet->setTitle("Лист 1");
        $users = $this->em->getRepository(User::class)->findAllClusterWithoutBAS();
//        dd($sheet->getHighestRow());
        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            $rfSubject = $userInfo->getRfSubject();
            $endRow = $sheet->getHighestRow()+1;

            if(in_array('ROLE_SMALL_CLUSTERS_LOT_1' ,$user->getRoles()))
            {
                $_role = 'СПО лот 1';
                $_grand = 7000000;
            }

            else if(in_array('ROLE_SMALL_CLUSTERS_LOT_2' ,$user->getRoles()))
            {
                $_role = 'СПО лот 2';
                $_grand = 60500000;
            }

            else if(in_array('ROLE_REGION' ,$user->getRoles()))
            {
                $_role = 'ОПЦ(К)';
                if(str_contains(implode($user->getUserTagsArray()), "Доп.отбор"))
                {
                    $_role = 'ОПЦ(К) (Доп.отбор)';
                }

                $_grand = 100000000;
            }
            else
                $_role = '?';

            $row = [
              $rfSubject->getName(),
              $userInfo->getEducationalOrganization(),
              $userInfo->getGrandOGRN(),
                $_role,
                $userInfo->getYear(),
                "=SUM(G$endRow:K$endRow)",
                $_grand,
                $userInfo->getFinancingFundsOfSubject()*1000,
                0,
                $userInfo->getExtraFundsEconomicSector()*1000,
                $userInfo->getExtraFundsOO()*1000,


            ];

            $sheet->fromArray($row, '', 'A'.$endRow, true);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size'  => 11,
                'name'  => 'Times New Roman'
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $end_cell = $sheet->getHighestRow()+1;
        $rangeTotal = 'A5:M'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        $row_arr = ['F','G','H','I','J', 'K'];
        foreach ($row_arr as $j){
            $sheet->getStyle($j."1:".$j.$end_cell)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        }

        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Объем финансового обеспечения.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function getUsers(string $role, int $year)
    {
        return $this->em->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role_1')
            ->andWhere('uf.year = :year')
            ->setParameter('role_1', "%$role%")
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
        ;
    }



}