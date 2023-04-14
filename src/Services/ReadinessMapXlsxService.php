<?php

namespace App\Services;

use App\Entity\ClusterAddresses;
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

class ReadinessMapXlsxService extends AbstractController
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

    public function getAddresses($user)
    {
        $entity_manger = $this->getDoctrine()->getManager();

        return $entity_manger->getRepository(ClusterAddresses::class)
            ->createQueryBuilder('ca')
            ->andWhere('ca.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function downloadTable(int $year)
    {
        $sheet_template = "../public/excel/readinessMap.xlsx";
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $today = new \DateTime('now');
        $index = 1;
        $users = $this->getUsersByYear($year);
        foreach ($users as $user)
        {
            $_data = [
                'F' => 0,
                'G' => 0,
                'H' => 0,
                'I' => 0,

            ];
            $_countZone =
                [
                    'Фасад' => 0,
                    'Входная группа' => 0,
                    'Холл (фойе)' => 0,
                    'Коридоры' => 0,
                    'Зона по видам работ' => 0,
                ];
            $adresses = $user->getClusterAddresses();
            $totalProcentZone = 0;
            $nearestDate = '';
            $lateDate = '';
            $zoneCount = 0;

            foreach ($adresses as $adress)
            {
                $zones = $adress->getClusterZones();
                foreach ($zones as $zone)
                {
                    if($zone->isDoNotTake())
                        continue;
                    $repair = $zone->getZoneRepair();
                    if($zoneCount == 0) {
                        $nearestDate = $repair->getEndDate();
                        $lateDate = $repair->getEndDate();
                    }


                    $_nearestDate = $repair->getEndDate();
                    $_lateDate = $repair->getEndDate();

                    if($nearestDate > $_nearestDate and $_nearestDate >= $today)
                        $nearestDate = $_nearestDate;
                    if($lateDate < $_lateDate )
                        $lateDate = $_lateDate;

                    if($zone->getType()->getName() == "Фасад")
                    {
                        $_data['F'] += $repair->getTotalPercentage();
                        $_countZone['Фасад'] += 1;
                    }
                    if($zone->getType()->getName() == "Входная группа")
                    {
                        $_data['G'] += $repair->getTotalPercentage();
                        $_countZone['Входная группа'] += 1;
                    }
                    if($zone->getType()->getName() == "Холл (фойе)")
                    {
                        $_data['H'] += $repair->getTotalPercentage();
                        $_countZone['Холл (фойе)'] += 1;
                    }
                    if($zone->getType()->getName() == "Коридоры")
                    {
                        $_data['I'] += $repair->getTotalPercentage();
                        $_countZone['Коридоры'] += 1;
                    }
                    if($zone->getType()->getName() == "Зона по видам работ")
                    {
                        $_countZone['Зона по видам работ'] += 1;
                        $totalProcentZone += $repair->getTotalPercentage();
                    }

                    $zoneCount++;
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
            $sheet->fromArray($user_info_arr, null, 'B'.$row);
            $count = 0;
            $dateMidFormula = "";
            if($_countZone['Фасад'] > 0)
            {
                $_data['F'] = $_data['F']/$_countZone['Фасад'];
                $count++;
            }
            if($_countZone['Входная группа'] > 0)
            {
                $_data['G'] = $_data['G']/$_countZone['Входная группа'];
                $count++;
            }
            if($_countZone['Холл (фойе)'] > 0)
            {
                $_data['H'] = $_data['H']/$_countZone['Холл (фойе)'];
                $count++;
            }
            if($_countZone['Коридоры'] > 0)
            {
                $_data['I'] = $_data['I']/$_countZone['Коридоры'];
                $count++;
            }
            if($nearestDate and $lateDate)
            {
                $dateMidFormula = "=(O$row+P$row)/2";
            }
            if($nearestDate)
            {
                $nearestDate = $nearestDate->format('d.m.Y');
            }

            if($lateDate)
            {
                $lateDate = $lateDate->format('d.m.Y');
            }



            $other_arr = [
                '',
                $_data['F']/100,
                $_data['G']/100,
                $_data['H']/100,
                $_data['I']/100,
                "=SUM(F$row:I$row)/$count",
                $_countZone['Зона по видам работ'],
                $totalProcentZone/100,
                "=L$row/K$row",
                "=(J$row+M$row)/2",
                $nearestDate,
                $lateDate,
                $user_info->getCurator(),


            ];
            $sheet->fromArray($other_arr, null, 'E'.$row);

            $sheet->getRowDimension($index+1)->setRowHeight(65);
            $index++;
            $curency_cell = ['E', 'F', 'G', 'H', 'I', 'J', 'L', 'M', 'N'];
            foreach ($curency_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
            }

            $spreadsheet->getActiveSheet()->getStyle("O$row:P$row")
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
//
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
        $rangeTotal = 'A2:Q'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Q')->getAlignment()->setVertical('center');

        $sheet = $spreadsheet->getSheetByName('Оборудование');
        $index = 1;
        foreach ($users as $user)
        {
            $adresses = $user->getClusterAddresses();
            $zoneCount = 0;
            $procentage = [
                'F' => 0,
                'G' => 0,
                'H' => 0,
                'I' => 0,
                'furniture' => 0,
                'furniture_fact' => 0,
                'PO' => 0,
                'PO_fact' => 0,
                'equipment' => 0,
                'equipment_fact' => 0,
                'furniture_put' => 0,
                'equipment_put' => 0,
                'PO_put' => 0,
            ];
            foreach ($adresses as $adress) {

                $zones = $adress->getClusterZones();
                foreach ($zones as $zone) {
                    if($zone->getType()->getName() == "Зона по видам работ")
                    {
                        $zoneCount++;
                        $arr = $zone->getCountOfEquipmentByType();
                        $procentage['F'] += ($arr['furniture'] > 0) ? ($arr['furniture_fact'] / $arr['furniture']) * 100 : 0;
                        $procentage['G'] += ($arr['PO'] > 0) ? ($arr['PO_fact'] / $arr['PO']) * 100 : 0;
                        $procentage['H'] += ($arr['equipment'] > 0) ? ($arr['equipment_fact'] / $arr['equipment']) * 100 : 0;

                        $total = $arr['furniture'] + $arr['PO'] + $arr['equipment'];
                        $total_put = $arr['furniture_put'] + $arr['equipment_put'] + $arr['PO_put'];
                        $procentage['I'] += ($total > 0) ? ($total_put/ $total) * 100 : 0;

                        $procentage['furniture'] += $arr['furniture'];
                        $procentage['furniture_fact'] += $arr['furniture_fact'];
                        $procentage['PO'] += $arr['PO'];
                        $procentage['PO_fact'] += $arr['PO_fact'];
                        $procentage['equipment'] += $arr['equipment'];
                        $procentage['equipment_fact'] += $arr['equipment_fact'];
                        $procentage['furniture_put'] += $arr['furniture_put'];
                        $procentage['equipment_put'] += $arr['equipment_put'];
                        $procentage['PO_put'] += $arr['PO_put'];
                    }


                }

            }
            $user_info = $user->getUserInfo();
            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $total = $procentage['furniture'] + $procentage['PO'] + $procentage['equipment'];
            $total_put = $procentage['furniture_put'] + $procentage['equipment_put'] + $procentage['PO_put'];
            $count = 0;
            if($procentage['furniture'] > 0)
                $count++;
            if($procentage['PO'] > 0)
                $count++;
            if($procentage['equipment'])
                $count++;

            $user_info_arr = [
                $user_info->getRfSubject()->getName(),
                $user_info->getDeclaredIndustry(),
                $user_info->getEducationalOrganization(),
                $zoneCount,
                round($procentage['F'], 2),
                round($procentage['G'], 2),
                round($procentage['H'], 2),
                round($procentage['I'], 2),
                ($procentage['furniture'] > 0) ? round(($procentage['furniture_fact'] / $procentage['furniture']) * 100, 2) : 0,
                ($procentage['PO'] > 0) ? round(($procentage['PO_fact'] / $procentage['PO']) * 100, 2) : 0,
                ($procentage['equipment'] > 0) ? round(($procentage['equipment_fact'] / $procentage['equipment']) * 100, 2) : 0,
                "=Sum(J$row:L$row)/$count",
                ($total > 0) ? round(($total_put / $total) * 100, 2) : 0,
                '-',
                '-',
                '-',
                '-',
                $user->getEquipmentDeliveryDeadline(),
                '-',
                '',
                $user_info->getCurator()

            ];
            $sheet->fromArray($user_info_arr, null, 'B'.$row);

            $sheet->getRowDimension($index+1)->setRowHeight(65);
            $index++;
        }
        $end_cell = $index;
        $rangeTotal = 'A2:V'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:V')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:V')->getAlignment()->setVertical('center');

        // Запись файла
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';


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