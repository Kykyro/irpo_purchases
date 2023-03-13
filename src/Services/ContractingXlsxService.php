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

class ContractingXlsxService extends AbstractController
{


    public function getUsersByYear($year){
        $entity_manger = $this->getDoctrine()->getManager();

        return $entity_manger->getRepository(User::class)->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'rf')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->setParameter('role', '%REGION%')
            ->setParameter('year', $year)
            ->orderBy('rf.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getProcedures($user)
    {
        $entity_manger = $this->getDoctrine()->getManager();

        return $entity_manger->getRepository(ProcurementProcedures::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->andWhere('u.id = :id')
            ->andWhere('a.isDeleted = :isDeleted')
            ->setParameter('id', $user->getId())
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
//            ->createQueryBuilder('pp')
//            ->andWhere('pp.user = :user')
//            ->andWhere('pp.isDeleted = :isDeleted')
//            ->setParameter('user', $user)
//            ->setParameter('isDeleted', false)
//            ->getQuery()
//            ->getResult();
    }

    public function downloadTable(int $year, \DateTime $today)
    {
        $sheet_template = "../public/excel/contracting.xlsx";
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();

        $grant = 100000000;
        $index = 1;
        $users = $this->getUsersByYear($year);
        foreach ($users as $user)
        {
            $procedures = $this->getProcedures($user);
            $_data = [
                'P' => 0,
                'Q' => 0,
                'R' => 0,
                'M' => 0,
                'N' => 0,
                'O' => 0,
                'I' => 0,
                'G' => 0
            ];
            foreach ($procedures as $procedure)
            {
                $date = new \DateTime('now');
                if($procedure->getPurchasesStatus($date) == 'contract')
                {
                    $_data['G'] += $procedure->getfinFederalFunds();
                    $_data['N'] += $procedure->getfinFundsOfSubject();
                    $_data['M'] += $procedure->getfinEmployersFunds();
                    $_data['O'] += $procedure->getfinFundsOfEducationalOrg();

                }
                elseif ($procedure->getPurchasesStatus($date) == 'announced')
                {
                    $_data['I'] += $procedure->getInitialFederalFunds();
                    $_data['P'] += $procedure->getInitialEmployersFunds();
                    $_data['Q'] += $procedure->getInitialFundsOfSubject();
                    $_data['R'] += $procedure->getInitialEducationalOrgFunds();
                }
            }
            $user_info = $user->getUserInfo();
            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $user_info_arr = [
                $user_info->getRfSubject()->getName(),
                $user_info->getDeclaredIndustry(),
                $user_info->getOrganization()
            ];
            $sheet->fromArray($user_info_arr, null, 'C'.$row);
            $other_arr = [
                '',
                $_data['G'],
                '=G'.$row.'/'.$grant,
                $_data['I'],
                '=I'.$row.'/'.$grant,
                '=G'.$row.'+I'.$row,
                '=H'.$row.'+J'.$row,
                $_data['M'],
                $_data['N'],
                $_data['O'],
                $_data['P'],
                $_data['Q'],
                $_data['R'],
                '',
                '',
                '',
            ];
            $sheet->fromArray($other_arr, null, 'F'.$row);

            $sheet->getStyle("H$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            $sheet->getStyle("J$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            $sheet->getStyle("L$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            $curency_cell = ['G', 'I', 'K', 'M', 'N', 'O', 'P', 'Q', 'R'];
            foreach ($curency_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }

            $sheet->getRowDimension($index+1)->setRowHeight(65);
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
        $end_cell = $index;
        $rangeTotal = 'A2:U'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:Z')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');

        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Контрактация - Кассовые расходы_'.$today->format('d-m-Y').'.xlsx';


        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }


    public function generateTable(int $year)
    {
        $sheet_template = "public/excel/contracting.xlsx";
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $today = new \DateTime('now');
        $grant = 100000000;
        $index = 1;
        $users = $this->getUsersByYear($year);
        foreach ($users as $user)
        {
            $procedures = $this->getProcedures($user);
            $_data = [
                'P' => 0,
                'Q' => 0,
                'R' => 0,
                'M' => 0,
                'N' => 0,
                'O' => 0,
                'I' => 0,
                'G' => 0
            ];
            foreach ($procedures as $procedure)
            {

                if($procedure->getDateOfConclusion())
                {
                    $_data['M'] += $procedure->getFactEmployersFunds();
                    $_data['N'] += $procedure->getFactFundsOfSubject();
                    $_data['O'] += $procedure->getFactFundsOfEducationalOrg();
                    $_data['G'] += $procedure->getFinFederalFunds();
                }
                elseif ($procedure->getPublicationDate())
                {
                    $_data['I'] += $procedure->getInitialFederalFunds();
                    $_data['P'] += $procedure->getInitialEmployersFunds();
                    $_data['Q'] += $procedure->getInitialFundsOfSubject();
                    $_data['R'] += $procedure->getInitialEducationalOrgFunds();
                }
            }
            $user_info = $user->getUserInfo();
            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $user_info_arr = [
                $user_info->getRfSubject()->getName(),
                $user_info->getDeclaredIndustry(),
                $user_info->getOrganization()
            ];
            $sheet->fromArray($user_info_arr, null, 'C'.$row);
            $other_arr = [
                '',
                $_data['G'],
                '=G'.$row.'/'.$grant,
                $_data['I'],
                '=I'.$row.'/'.$grant,
                '=G'.$row.'+I'.$row,
                '=H'.$row.'+J'.$row,
                $_data['M'],
                $_data['N'],
                $_data['O'],
                $_data['P'],
                $_data['Q'],
                $_data['R'],
                '',
                '',
                '',
            ];
            $sheet->fromArray($other_arr, null, 'F'.$row);

            $sheet->getRowDimension($index+1)->setRowHeight(65);
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
        $end_cell = $index;
        $rangeTotal = 'A2:U'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Контрактация - Кассовые расходы_'.$today->format('d-m-Y').'_'.uniqid().'.xlsx';




        if (!file_exists($this->getParameter('contracting_tables_directory'))) {
            mkdir($this->getParameter('contracting_tables_directory'), 0777, true);
        }

        $writer->save($this->getParameter('contracting_tables_directory').'/'.$fileName);

        return $fileName;
    }



}