<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\PurchasesDump;
use App\Entity\RfSubject;
use App\Entity\TotalBudget;
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


    public function getUsersByYear($year, $role, $tags){
        $entity_manger = $this->getDoctrine()->getManager();
        $query = $entity_manger->getRepository(User::class)->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'rf')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->setParameter('role', $role)
            ->setParameter('year', $year)
            ;

        if($tags)
        {
            $query
                ->leftJoin('u.userTags', 't')
                ->andWhere('t.id = :tags')
                ->setParameter('tags', $tags->getId());
        }
        return $query
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
    }

    public function getTotalProcentageRow($year, $role, $startCell)
    {
        $entity_manger = $this->getDoctrine()->getManager();

        $totalBudget = $entity_manger->getRepository(TotalBudget::class)
            ->createQueryBuilder('a')
            ->andWhere('a.role LIKE :role')
            ->andWhere('a.year = :year')
            ->setParameter('role', $role)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
        if(count($totalBudget) > 0)
        {
            $totalBudget = $totalBudget[0];
            $row = $startCell-1;
            return [
                $totalBudget->getFederal() ? "=E$row"."/".$totalBudget->getFederal() : '',
                '',
                $totalBudget->getFederal() ? "=G$row"."/".$totalBudget->getFederal(): '',
                '',
                "=E$startCell+G$startCell",
                '',
                $totalBudget->getEmployeers() ? "=K$row"."/".$totalBudget->getEmployeers() : '',
                $totalBudget->getSubject() ? "=L$row"."/".$totalBudget->getSubject() : '',
                $totalBudget->getEdicational()? "=M$row"."/".$totalBudget->getEdicational() : '',
                $totalBudget->getEmployeers() ? "=N$row"."/".$totalBudget->getEmployeers() : '',
                $totalBudget->getSubject() ? "=O$row"."/".$totalBudget->getSubject() : '',
                $totalBudget->getEdicational() ? "=P$row"."/".$totalBudget->getEdicational() : '',

                $totalBudget->getFederal() ? "=Q$row"."/".$totalBudget->getFederal() : '',
                $totalBudget->getEmployeers() ? "=R$row"."/".$totalBudget->getEmployeers() : '',
                $totalBudget->getSubject() ? "=S$row"."/".$totalBudget->getSubject() : '',
                $totalBudget->getEdicational() ? "=T$row"."/".$totalBudget->getEdicational() : '',
            ];
        }
        else{
            return [];
        }

    }


    public function downloadTable(int $year, \DateTime $today = null, string $role = 'cluster', $save = null, $tags = null)
    {

        $sheet_template = $this->getParameter('contrating_template_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        if(is_null($today))
            $today = new \DateTime('now');
        $grant = 100000000;
        $index = 1;

        if($role == 'lot_1')
        {
            $fileName = 'Контрактация лот 1 '.$year." год ".$today->format('d-m-Y');
            $grant = 70000000;
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_1%', $tags);
        }

        else if($role == 'lot_2')
        {
            $fileName = 'Контрактация лот 2 '.$year." год ".$today->format('d-m-Y');
            $grant = 60500000;
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_2%', $tags);
        }
        else if($role == 'bas')
        {
            $fileName = 'Контрактация БАС '.$year." год ".$today->format('d-m-Y');
            $grant = 60500000;
            $users = $this->getUsersByYear($year, '%ROLE_BAS%', $tags);
        }
        else
        {
            $fileName = 'Контрактация ОПЦ '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%REGION%', $tags);
        }
        $curency_cell = ['E', 'G', 'I', 'K', 'M', 'N', 'O', 'P', 'L', 'Q', 'R', 'S', 'T'];
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
                'G' => 0,
                'fact_FB' => 0,
                'fact_RD' => 0,
                'fact_RF' => 0,
                'fact_OO' => 0,
            ];
            foreach ($procedures as $procedure)
            {

                if($procedure->getPurchasesStatus($today) == 'contract')
                {
                    $_data['G'] += $procedure->getfinFederalFunds();
                    $_data['N'] += $procedure->getfinFundsOfSubject();
                    $_data['M'] += $procedure->getfinEmployersFunds();
                    $_data['O'] += $procedure->getfinFundsOfEducationalOrg();

                    $_data['fact_FB'] += $procedure->getFactFederalFunds();
                    $_data['fact_RF'] += $procedure->getFactFundsOfSubject();
                    $_data['fact_RD'] += $procedure->getFactEmployersFunds();
                    $_data['fact_OO'] += $procedure->getFactFundsOfEducationalOrg();

                }
                elseif ($procedure->getPurchasesStatus($today) == 'announced')
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
                $user_info->getEducationalOrganization()
            ];
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);
            $other_arr = [

                $_data['G'],
                '=E'.$row.'/'.$grant,
                $_data['I'],
                '=G'.$row.'/'.$grant,
                '=E'.$row.'+G'.$row,
                '=F'.$row.'+H'.$row,
                $_data['M'],
                $_data['N'],
                $_data['O'],
                $_data['P'],
                $_data['Q'],
                $_data['R'],
                $_data['fact_FB'],
                $_data['fact_RF'],
                $_data['fact_RD'],
                $_data['fact_OO'],
                $user_info->getCurator(),
                '',
                '',
            ];
            $sheet->fromArray($other_arr, null, 'E'.$row, true);

            $sheet->getStyle("F$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            $sheet->getStyle("H$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            $sheet->getStyle("J$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
//            $curency_cell = ['E', 'G', 'I', 'K', 'M', 'N', 'O', 'P'];
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
        $rangeTotal = 'A2:U'.($end_cell+2);
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        $sumRow = $end_cell + 1;
//        $sheet->setCellValue('G'.$sumRow, "=SUM(G2:G$end_cell)");
//        $sheet->setCellValue('I'.$sumRow, "=SUM(I2:I$end_cell)");
//        $sheet->setCellValue('K'.$sumRow, "=SUM(K2:K$end_cell)");
//        $sheet->setCellValue('M'.$sumRow, "=SUM(M2:M$end_cell)");
//        $sheet->setCellValue('N'.$sumRow, "=SUM(N2:M$end_cell)");
//        $sheet->setCellValue('O'.$sumRow, "=SUM(O2:O$end_cell)");
//        $sheet->setCellValue('P'.$sumRow, "=SUM(P2:P$end_cell)");
//        $sheet->setCellValue('Q'.$sumRow, "=SUM(Q2:Q$end_cell)");
//        $sheet->setCellValue('R'.$sumRow, "=SUM(R2:R$end_cell)");

//        $curency_cell = ['G', 'I', 'K', 'M', 'N', 'O', 'P', 'Q', 'R'];
        foreach ($curency_cell as $cell)
        {
            $sheet->setCellValue($cell.$sumRow, "=SUM({$cell}2:{$cell}$end_cell)");
            $sheet->getStyle("$cell$sumRow")->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            $sheet->getStyle("$cell".($sumRow+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        }
        
        $sheet->getStyle('A:Z')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');

        $totalProcentageCell = (isset($row)) ? ($row+2) : 3;
        if($role == 'lot_1')
            $totalProcentageRow = $this->getTotalProcentageRow($year, '%ROLE_SMALL_CLUSTER_LOT_1%', $totalProcentageCell);
        else if($role == 'lot_2')
            $totalProcentageRow = $this->getTotalProcentageRow($year, '%ROLE_SMALL_CLUSTER_LOT_2%', $totalProcentageCell);
        else
            $totalProcentageRow = $this->getTotalProcentageRow($year, '%REGION%', $totalProcentageCell);


        $sheet->fromArray($totalProcentageRow, null, 'E'.$totalProcentageCell);



        // Запись файла
        $writer = new Xlsx($spreadsheet);



        if($save)
        {
//            dd();
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('contracting_tables_directory'))) {
                mkdir($this->getParameter('contracting_tables_directory'), 0777, true);
            }

            $writer->save($this->getParameter('contracting_tables_directory').'/'.$fileName);

            return $fileName;
//            contracting_tables_directory
//            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }
        else{
//            dd();
            $fileName = $fileName.'.xlsx';


            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);

            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function downloadTableBAS(int $year, \DateTime $today = null, string $role = 'cluster', $save = null)
    {

        $sheet_template = $this->getParameter('contrating_bas_template_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        if(is_null($today))
            $today = new \DateTime('now');

        $index = 1;


        $fileName = 'Контрактация БАС '.$year." год ".$today->format('d-m-Y');
        $grant = 60500000;
        $users = $this->getUsersByYear($year, '%ROLE_BAS%');
//        dd($sheet->getHighestRow());
        $curency_cell = ['C', 'D', 'F', 'H', 'J', 'K', 'M', 'O'];
        $procent_cell = ['E', 'G', 'I', 'L', 'N', 'P'];
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

                if($procedure->getPurchasesStatus($today) == 'contract')
                {
                    $_data['G'] += $procedure->getfinFederalFunds();
                    $_data['N'] += $procedure->getfinFundsOfSubject();
                    $_data['M'] += $procedure->getfinEmployersFunds();
                    $_data['O'] += $procedure->getfinFundsOfEducationalOrg();

                }
                elseif ($procedure->getPurchasesStatus($today) == 'announced')
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
            ];
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);
            $other_arr = [

                $user_info->getFedFundsGrant(),
                $_data['G'],
                "=D$row/C$row",
                $_data['I'],
                "=F$row/C$row",
                "=D$row+F$row",
                "=H$row/C$row",
                $user_info->getRegionFundsGrant(),
                $_data['N'],
                "=K$row/J$row",
                $_data['Q'],
                "=M$row/J$row",
                "=K$row+M$row",
                "=O$row/J$row",
                $user_info->getCurator(),

            ];
            $sheet->fromArray($other_arr, null, 'C'.$row, true);

            foreach ($procent_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            }
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

        $end_cell = $sheet->getHighestRow();
        $rangeTotal = 'A2:S'.($end_cell);
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        $sumRow = $sheet->getHighestRow()+1;

        foreach ($curency_cell as $cell)
        {
            $sheet->setCellValue($cell.$sumRow, "=SUM({$cell}3:{$cell}$end_cell)");
            $sheet->getStyle("$cell$sumRow")->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            $sheet->getStyle("$cell".($sumRow+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        }

        $sheet->getStyle('A:Z')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');

//        $totalProcentageCell = (isset($row)) ? ($row+2) : 3;
//        if($role == 'lot_1')
//            $totalProcentageRow = $this->getTotalProcentageRow($year, '%ROLE_SMALL_CLUSTER_LOT_1%', $totalProcentageCell);
//        else if($role == 'lot_2')
//            $totalProcentageRow = $this->getTotalProcentageRow($year, '%ROLE_SMALL_CLUSTER_LOT_2%', $totalProcentageCell);
//        else
//            $totalProcentageRow = $this->getTotalProcentageRow($year, '%REGION%', $totalProcentageCell);
//
//
//        $sheet->fromArray($totalProcentageRow, null, 'E'.$totalProcentageCell);




        // Запись файла
        $writer = new Xlsx($spreadsheet);



        if($save)
        {
//            dd();
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('contracting_tables_directory'))) {
                mkdir($this->getParameter('contracting_tables_directory'), 0777, true);
            }

            $writer->save($this->getParameter('contracting_tables_directory').'/'.$fileName);

            return $fileName;
//            contracting_tables_directory
//            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }
        else{
//            dd();
            $fileName = $fileName.'.xlsx';


            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);

            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
}