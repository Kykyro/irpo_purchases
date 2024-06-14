<?php

namespace App\Services;

use App\Entity\Employers;
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

class XlsxClusterIndustryService extends AbstractController
{
    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;
    }

    public function generate($year, $role)
    {
        $users = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->setParameter('year', $year)
            ->setParameter('role', "%$role%")
            ->orderBy('uf.rf_subject', 'ASC')
            ->getQuery()
            ->getResult();
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
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $industrys = $this->getIndustres($users);
        $headerRow = [
            'Наименование субъекта Российской Федерации',

        ];
        $headerRow = array_merge($headerRow, $industrys);
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("sheet");
        $sheet->fromArray($headerRow);

        if(count($users))
        {
            $region = $users[0]->getUserInfo()->getRfSubject()->getName();
            $row = [$region];
            foreach ($industrys as $industry)
            {
                array_push($row, 0);
            }
            foreach ($users as $key => $user)
            {
                if($region == $user->getUserInfo()->getRfSubject()->getName())
                {

                    $_index = 0;
                    foreach ($industrys as $industry)
                    {
                        $_index++;
                        if($user->getUserInfo()->getDeclaredIndustry() == $industry)
                        {
                            $row[$_index]++;
                        }
                    }
                }
                else
                {
                    $sheet->fromArray($row, '-', "A".($sheet->getHighestRow()+1) );
                    $region = $user->getUserInfo()->getRfSubject()->getName();
                    $row = [$region];
                    foreach ($industrys as $industry)
                    {
                        array_push($row, 0);
                    }
                    $_index = 0;
                    foreach ($industrys as $industry)
                    {
                        $_index++;
                        if($user->getUserInfo()->getDeclaredIndustry() == $industry)
                        {
                            $row[$_index]++;
                        }
                    }

                }
                if ($key === array_key_last($users)) {
                    $sheet->fromArray($row, '-', "A".($sheet->getHighestRow()+1) );
                }
            }
        }


        $index = $sheet->getHighestRow()+1;
        $highestColumn = $sheet->getHighestColumn();
        $rangeTotal = 'A1:'.$highestColumn.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(50);


        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'excel.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
    public function generateAll()
    {
        $users = $this->entity_manager->getRepository(User::class)->findAllClusterWithoutBAS();

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
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];

        $headerRow = [
            'Отрасль',
            'Итого округов',
            'Итого регионов',
            'Итого кластеров',
            'Итого работодателей',
            'Итого образовательных организаций',
            'Итого средств от реального сектора экономики',
            'Итого средств от субъектов',
            'Итого средств от ОО',
            'Итого профессий и специальностей',
            'Итого зон по виду работ',
            'Грантополучатель'

        ];
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($headerRow);

        $industrys = [];

        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            $rfSubject = $userInfo->getRfSubject();
            $industry = $userInfo->getDeclaredIndustry();
            $row = [
                $rfSubject->getDistrict(),
                $rfSubject->getName(),
                $userInfo->getListOfEmployers(),
                $userInfo->getListOfEdicationOrganization(),
                $userInfo->getExtraFundsEconomicSector()*1000,
                $userInfo->getFinancingFundsOfSubject()*1000,
                $userInfo->getExtraFundsOO()*1000,
                $userInfo->getUGPS(),
                $user->getSortedWorkZones(),
                $userInfo->getEducationalOrganization(),

            ];
            if(array_key_exists($industry, $industrys))
            {
                array_push($industrys[$industry], $row);
            }
            else
            {
                $industrys[$industry] = [];
                array_push($industrys[$industry], $row);
            }
        }
        foreach ($industrys as $key => $value )
        {

            $title = mb_strimwidth($key, 0, 31, "...");
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $title);
            $spreadsheet->addSheet($myWorkSheet, 0);

            $sheetIndex = $spreadsheet->getIndex(
                $spreadsheet->getSheetByName($title)
            );

            $spreadsheet->setActiveSheetIndex($sheetIndex);

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($headerRow);


            foreach ($value as $i)
            {

                $row = [
                    $key,
                    $i[0],
                    $i[1],
                    1,
                    '',
                    '',
                    $i[4],
                    $i[5],
                    $i[6],
                    '',
                    '',
                    $i[9],
                ];
                $endRow = $sheet->getHighestRow() +1;
                $sheet->fromArray($row, '', 'A'.$endRow, true);

                if(is_array($i[2]))
                {
                    $_endRow = $endRow;
                    foreach ($i[2] as $j)
                    {
                        $sheet->setCellValue("E$_endRow", $j);
                        $_endRow++;

                    }
                }
                if(is_array($i[3]))
                {
                    $_endRow = $endRow;
                    foreach ($i[3] as $j)
                    {
                        $sheet->setCellValue("F$_endRow", $j);
                        $_endRow++;

                    }

                }
                if(is_array($i[7]))
                {
                    $_endRow = $endRow;
                    foreach ($i[7] as $j)
                    {
                        $sheet->setCellValue("J$_endRow", $j);
                        $_endRow++;

                    }

                }
                if(is_array($i[8]))
                {
                    $_endRow = $endRow;
                    foreach ($i[8] as $j)
                    {
                        $sheet->setCellValue("K$_endRow", $j->getName());
                        $_endRow++;

                    }

                }

            }

            $endRow = $sheet->getHighestRow() +1;
            $sheet->fromArray($headerRow, '', 'A'.$endRow, true);


            $func = function($value) {
                return $value[0];
            };



            $row = [
                $key,
                $this-> sumResult($sheet, 'B', $endRow),
                $this-> sumResult($sheet, 'C', $endRow),
                 "=SUM(D2:D".($endRow-1).")",
                $this-> sumResult($sheet, 'E', $endRow),
                $this-> sumResult($sheet, 'F', $endRow),
                 "=SUM(G2:G".($endRow-1).")",
                 "=SUM(H2:H".($endRow-1).")",
                 "=SUM(I2:I".($endRow-1).")",
                $this-> sumResult($sheet, 'J', $endRow),
             ];
            $sheet->fromArray($row, '', 'A'.($endRow+1), true);
            $index = $sheet->getHighestRow()+1;
            $highestColumn = $sheet->getHighestColumn();
            $rangeTotal = 'A1:'.$highestColumn.$index;
            $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
            $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension('A')->setWidth(50);
        }




        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'excel.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function getRoles($employer)
    {
        $userInfos = $employer->getUserInfos();
        if(count($userInfos))
        {
            $userInfoId = $userInfos[0]->getId();

            $user =  $this->entity_manager->getRepository(User::class)
                ->createQueryBuilder('u')
                ->leftJoin('u.user_info', 'uf')
                ->andWhere('uf.id LIKE :id')
                ->setParameter('id', $userInfoId)
                ->getQuery()
                ->getResult()
                ;
            if(count($user))
            {
                $roles =  implode(" | ", $user[0]->getRoles());
                if(str_contains($roles, "ROLE_SMALL_CLUSTERS"))
                    return "Кластер СПО";
                else if(str_contains($roles, "ROLE_REGION"))
                    return "ОПЦ(К)";
                else
                    return "";

            }
            else{
                return "";
            }

        }
        else{
            return "";
        }
    }

    private function getIndustres($users)
    {
        $arr = [];
        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            array_push($arr, $user_info->getDeclaredIndustry());
        }
        $arr = array_unique($arr);
        $indystres = [];
        foreach ($arr as $a)
        {
            array_push($indystres, $a);
        }
        sort($indystres);
        return $indystres;
    }

    private function sumResult($sheet, $l, $endRow)
    {


        $func = function($value) {
            return $value[0];
        };

        $_b = array_map($func, $sheet->rangeToArray("$l"."2:$l".($endRow-1)));
        $emptyRemoved_b = array_filter($_b);

        return count(array_unique($emptyRemoved_b));
    }
}