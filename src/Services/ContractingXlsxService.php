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


    public function getUsersByYear($year, $role){
        $entity_manger = $this->getDoctrine()->getManager();

        return $entity_manger->getRepository(User::class)->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'rf')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->setParameter('role', $role)
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
                "=G$row/".$totalBudget->getFederal(),
                '',
                "=I$row/".$totalBudget->getFederal(),
                '',
                "=G$startCell+I$startCell",
                '',
                "=M$row/".$totalBudget->getEmployeers(),
                "=N$row/".$totalBudget->getSubject(),
                "=O$row/".$totalBudget->getEdicational(),
                "=P$row/".$totalBudget->getEmployeers(),
                "=Q$row/".$totalBudget->getSubject(),
                "=R$row/".$totalBudget->getEdicational(),
            ];
        }
        else{
            return [];
        }

    }


    public function downloadTable(int $year, \DateTime $today = null, string $role = 'cluster', bool $save = false)
    {
        $sheet_template = "../public/excel/contracting.xlsx";
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();

        $grant = 100000000;
        $index = 1;

        if($role == 'lot_1')
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_1%');
        else if($role == 'lot_1')
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_2%');
        else
            $users = $this->getUsersByYear($year, '%REGION%');

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
                if(is_null($today))
                    $today = new \DateTime('now');
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
                $user_info->getDeclaredIndustry(),
                $user_info->getEducationalOrganization()
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
        $rangeTotal = 'A2:S'.($end_cell+2);
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        $sumRow = $end_cell + 1;
        $sheet->setCellValue('G'.$sumRow, "=SUM(G2:G$end_cell)");
        $sheet->setCellValue('I'.$sumRow, "=SUM(I2:I$end_cell)");
        $sheet->setCellValue('K'.$sumRow, "=SUM(K2:K$end_cell)");
        $sheet->setCellValue('M'.$sumRow, "=SUM(M2:M$end_cell)");
        $sheet->setCellValue('N'.$sumRow, "=SUM(N2:M$end_cell)");
        $sheet->setCellValue('O'.$sumRow, "=SUM(O2:O$end_cell)");
        $sheet->setCellValue('P'.$sumRow, "=SUM(P2:P$end_cell)");
        $sheet->setCellValue('Q'.$sumRow, "=SUM(Q2:Q$end_cell)");
        $sheet->setCellValue('R'.$sumRow, "=SUM(R2:R$end_cell)");

        $curency_cell = ['G', 'I', 'K', 'M', 'N', 'O', 'P', 'Q', 'R'];
        foreach ($curency_cell as $cell)
        {
            $sheet->getStyle("$cell$sumRow")->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            $sheet->getStyle("$cell".($sumRow+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        }
        
        $sheet->getStyle('A:Z')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');

        $totalProcentageCell = (isset($row)) ? ($row+2) : 3;
        if($role == 'lot_1')
            $totalProcentageRow = $this->getTotalProcentageRow($year, '%ROLE_SMALL_CLUSTERS_LOT_1%', $totalProcentageCell);
        else if($role == 'lot_2')
            $totalProcentageRow = $this->getTotalProcentageRow($year, '%ROLE_SMALL_CLUSTERS_LOT_2%', $totalProcentageCell);
        else
            $totalProcentageRow = $this->getTotalProcentageRow($year, '%REGION%', $totalProcentageCell);


        $sheet->fromArray($totalProcentageRow, null, 'G'.$totalProcentageCell);


        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Контрактация - Кассовые расходы_'.$today->format('d-m-Y').'.xlsx';


        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        if($save)
        {
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }
        else{
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
}