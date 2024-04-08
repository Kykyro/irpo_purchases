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
    public function getUsersByYearPaginator($year, $role, $start=0, $step,$tags = null){
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
            ->setFirstResult($start)
            ->setMaxResults($step)
            ->getQuery()
            ->getResult();

    }

    public function getUsersByYearOffset($year, $role){
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

    public function downloadTable(int $year, string $role = 'cluster', $save = false, $tags = null)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('readiness_map_table_template_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $today = new \DateTime('now');
        $index = 1;


        if($role == 'lot_1')
        {
            $fileName = 'Карта готовности лот 1 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_1%', $tags);
        }

        else if($role == 'lot_2')
        {
            $fileName = 'Карта готовности лот 2 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_2%', $tags);
        }

        else
        {
            $fileName = 'Карта готовности ОПЦ '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%REGION%', $tags);
        }


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
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);
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
            $sheet->fromArray($other_arr, null, 'E'.$row, true);

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
        $sheet->removeColumn('E');

        $sheet = $spreadsheet->getSheetByName('Оборудование');
        $index = 1;
        $proc_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'K'];
        if($role != 'cluster')
        {
            $sheet->insertNewColumnBefore('M');
            $sheet->getStyle('M1')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'font' => [
                    'size'  => 12,
                    'name'  => 'Times New Roman',
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '70ad47']
                ]
            ])->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension('M')->setWidth(20);
            $sheet->setCellValue('M1', 'Средний % укомплектованности учебными пособиями');
            $proc_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'K', 'O'];
        }

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

                'allowance' => 0,
                'allowance_fact' => 0,
                'allowance_put' => 0,
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
//                        $procentage['allowance_mid'] += ($arr['allowance'] > 0) ? ($arr['allowance_fact'] / $arr['allowance']) * 100 : 0;

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

                        $procentage['allowance'] += $arr['allowance'];
                        $procentage['allowance_fact'] += $arr['allowance_fact'];
                        $procentage['allowance_put'] += $arr['allowance_put'];
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
            if($procentage['allowance'])
                $count++;

            if($role != 'cluster')
                $user_info_arr = [
                    $user_info->getRfSubject()->getName(),
                    $user_info->getDeclaredIndustry(),
                    $user_info->getEducationalOrganization(),
                    $zoneCount,
                    round($procentage['F'], 2)/100,
                    round($procentage['G'], 2)/100,
                    round($procentage['H'], 2)/100,
                    round($procentage['I'], 2)/100,
                    $this->midleProc($procentage['furniture'], $procentage['furniture_fact']),
                    $this->midleProc($procentage['PO'], $procentage['PO_fact']),
                    $this->midleProc($procentage['equipment'], $procentage['equipment_fact']),
                    $this->midleProc($procentage['allowance'], $procentage['allowance_fact']),
                    "=Sum(J$row:M$row)/$count",
                    ($count > 0) ? ($this->midleProc($procentage['furniture'], $procentage['furniture_put'])+
                            $this->midleProc($procentage['PO'], $procentage['PO_put'])+
                            $this->midleProc($procentage['equipment'], $procentage['equipment_put'])+
                            $this->midleProc($procentage['allowance'], $procentage['allowance_put'])
                        )/$count : 0,
                    '-',
                    '-',
                    '-',
                    '-',
                    $user->getEquipmentDeliveryDeadline(),
                    '-',
                    '',
                    $user_info->getCurator()

                ];
            else
                $user_info_arr = [
                    $user_info->getRfSubject()->getName(),
                    $user_info->getDeclaredIndustry(),
                    $user_info->getEducationalOrganization(),
                    $zoneCount,
                    round($procentage['F'], 2)/100,
                    round($procentage['G'], 2)/100,
                    round($procentage['H'], 2)/100,
                    round($procentage['I'], 2)/100,
                    $this->midleProc($procentage['furniture'], $procentage['furniture_fact']),
                    $this->midleProc($procentage['PO'], $procentage['PO_fact']),
                    $this->midleProc($procentage['equipment'], $procentage['equipment_fact']),
                    "=Sum(J$row:L$row)/$count",
                    ($count > 0) ? ($this->midleProc($procentage['furniture'], $procentage['furniture_put'])+
                    $this->midleProc($procentage['PO'], $procentage['PO_put'])+
                    $this->midleProc($procentage['equipment'], $procentage['equipment_put']))/$count : 0,
                    '-',
                    '-',
                    '-',
                    '-',
                    $user->getEquipmentDeliveryDeadline(),
                    '-',
                    '',
                    $user_info->getCurator()

                ];
            $sheet->fromArray($user_info_arr, "-", 'B'.$row, true);

            foreach ($proc_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
            }

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

        if($save)
        {
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
            }

            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);

            return $fileName;
        }
        else{
//            $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }

    public function downloadTableNew(int $year, string $role = 'cluster', $save = false, $start=0, $step = 200, $tags = null)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('readiness_map_table_template_file_new');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $today = new \DateTime('now');
        $index = 1;


        if($role == 'lot_1')
        {
            $fileName = 'Карта готовности лот 1 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%ROLE_SMALL_CLUSTERS_LOT_1%', $start, $step, $tags);
        }

        else if($role == 'lot_2')
        {
            $fileName = 'Карта готовности лот 2 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%ROLE_SMALL_CLUSTERS_LOT_2%', $start, $step, $tags);
        }

        else
        {
            $fileName = 'Карта готовности ОПЦ '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%REGION%', $start, $step, $tags);
        }


        foreach ($users as $user)
        {
            $adresses = $user->getClusterAddresses();

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
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);


            if($lateDate)
            {
                $lateDate = $lateDate->format('d.m.Y');
            }

            $other_arr = [
                $zoneCount, // Количество зон под виды работ
                $user->getMidRepairByCommon()/100, // Средний % рем. работ по общим помещениям
                $user->getMidRepairByZone()/100, // Средний % рем. работ
                "=(F$row+G$row)/2", // Средний % рем. работ в целом
                $lateDate, // Крайняя дата окончания рем. работ
                $user_info->getCurator(),


            ];
            $sheet->fromArray($other_arr, null, 'E'.$row, true);

            $sheet->getRowDimension($index+1)->setRowHeight(65);
            $index++;
            $curency_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N'];
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
        $rangeTotal = 'A2:J'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Q')->getAlignment()->setVertical('center');


        // ============================================
        // ===========ОБОРУДОВАНИЕ===============
        // ============================================
        $sheet = $spreadsheet->getSheetByName('Оборудование');
        $index = 1;
        $proc_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'K'];


        foreach ($users as $user)
        {
            $check = $user->getReadinessMapChecks()->last();
            if($check)
            {
                $status = $check->getStatus()->last();

            }
            else
            {
                $status = false;
            }
            $adresses = $user->getClusterAddresses();
            $zoneCount = 0;
            $procentage = [
                'furniture' => 0,
                'furniture_fact' => 0,
                'PO' => 0,
                'PO_fact' => 0,
                'equipment' => 0,
                'equipment_fact' => 0,
                'furniture_put' => 0,
                'equipment_put' => 0,
                'PO_put' => 0,

                'allowance' => 0,
                'allowance_fact' => 0,
                'allowance_put' => 0,
            ];
            foreach ($adresses as $adress) {

                $zones = $adress->getClusterZones();
                foreach ($zones as $zone) {
                    if($zone->getType()->getName() == "Зона по видам работ")
                    {
                        $zoneCount++;
                        $arr = $zone->getCountOfEquipmentByType();

                        $procentage['furniture'] += $arr['furniture'];
                        $procentage['furniture_fact'] += $arr['furniture_fact'];
                        $procentage['PO'] += $arr['PO'];
                        $procentage['PO_fact'] += $arr['PO_fact'];
                        $procentage['equipment'] += $arr['equipment'];
                        $procentage['equipment_fact'] += $arr['equipment_fact'];
                        $procentage['furniture_put'] += $arr['furniture_put'];
                        $procentage['equipment_put'] += $arr['equipment_put'];
                        $procentage['PO_put'] += $arr['PO_put'];

                        $procentage['allowance'] += $arr['allowance'];
                        $procentage['allowance_fact'] += $arr['allowance_fact'];
                        $procentage['allowance_put'] += $arr['allowance_put'];
                    }


                }

            }
            $user_info = $user->getUserInfo();
            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $total = $procentage['furniture'] + $procentage['PO'] + $procentage['equipment'];


            $user_info_arr = [
                $user_info->getRfSubject()->getName(),
                $user_info->getDeclaredIndustry(),
                $user_info->getEducationalOrganization(),
                $zoneCount,
                $total > 0 ? (
                        $procentage['furniture_fact'] +
                        $procentage['PO_fact'] +
                        $procentage['equipment_fact'] +
                        $procentage['allowance_fact']
                    )/$total : '0',     // Средний % укомплектованности МТБ
                $total > 0 ? (
                        $procentage['furniture_put'] +
                        $procentage['PO_put'] +
                        $procentage['equipment_put'] +
                        $procentage['allowance_put']
                    )/$total : '0',  // Средний % введения в эксплуатацию
                $user->getEquipmentDeliveryDeadline(), // Крайняя дата завершения комплектования
                $status ? $status->getStatus() : '', // Статус проверки
                $status ? $status->getComment() : '', // Комментарии куратора
                $user_info->getCurator() // Куратор
            ];

            $sheet->fromArray($user_info_arr, "-", 'B'.$row, true);

            foreach ($proc_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
            }

            $sheet->getRowDimension($index+1)->setRowHeight(65);
            $index++;
        }
        $end_cell = $index;
        $rangeTotal = 'A2:K'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:V')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:V')->getAlignment()->setVertical('center');


        // Запись файла
        $writer = new Xlsx($spreadsheet);

        if($save)
        {
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
            }

            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);

            return $fileName;
        }
        else{
//            $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function downloadTableNew2(int $year, string $role = 'cluster', $save = false, $start=0, $step = 200)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('readiness_map_table_template_file_new');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $today = new \DateTime('now');
        $index = 1;
        $proc_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'K'];
        $curency_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N'];
        if($role == 'lot_1')
        {
            $fileName = 'Карта готовности лот 1 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%ROLE_SMALL_CLUSTERS_LOT_1%', $start, $step);
        }

        else if($role == 'lot_2')
        {
            $fileName = 'Карта готовности лот 2 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%ROLE_SMALL_CLUSTERS_LOT_2%', $start, $step);
        }

        else
        {
            $fileName = 'Карта готовности ОПЦ '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%REGION%', $start, $step);
        }


        foreach ($users as $user)
        {
            $check = $user->getReadinessMapChecks()->last();
            if($check)
            {
                $status = $check->getStatus()->last();

            }
            else
            {
                $status = false;
            }
            $user_info = $user->getUserInfo();

            $zoneCount = 0;
            $procentage = [
                'furniture' => 0,
                'furniture_fact' => 0,
                'PO' => 0,
                'PO_fact' => 0,
                'equipment' => 0,
                'equipment_fact' => 0,
                'furniture_put' => 0,
                'equipment_put' => 0,
                'PO_put' => 0,

                'allowance' => 0,
                'allowance_fact' => 0,
                'allowance_put' => 0,
            ];
            // =================================
            $adresses = $user->getClusterAddresses();

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

                    $zoneCount++;

                    if($zone->getType()->getName() == "Зона по видам работ")
                    {
                        $zoneCount;
                        $arr = $zone->getCountOfEquipmentByType();

                        $procentage['furniture'] += $arr['furniture'];
                        $procentage['furniture_fact'] += $arr['furniture_fact'];
                        $procentage['PO'] += $arr['PO'];
                        $procentage['PO_fact'] += $arr['PO_fact'];
                        $procentage['equipment'] += $arr['equipment'];
                        $procentage['equipment_fact'] += $arr['equipment_fact'];
                        $procentage['furniture_put'] += $arr['furniture_put'];
                        $procentage['equipment_put'] += $arr['equipment_put'];
                        $procentage['PO_put'] += $arr['PO_put'];

                        $procentage['allowance'] += $arr['allowance'];
                        $procentage['allowance_fact'] += $arr['allowance_fact'];
                        $procentage['allowance_put'] += $arr['allowance_put'];
                    }
                }
                // =============================
//                $zones = $adress->getClusterZones();


            }

            $sheet = $spreadsheet->getSheetByName('Ремонтные работы');

            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $user_info_arr = [
                $user_info->getRfSubject()->getName(),
                $user_info->getDeclaredIndustry(),
                $user_info->getEducationalOrganization()

            ];
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);


            if($lateDate)
            {
                $lateDate = $lateDate->format('d.m.Y');
            }

            $other_arr = [
                $zoneCount, // Количество зон под виды работ
                $user->getMidRepairByCommon()/100, // Средний % рем. работ по общим помещениям
                $user->getMidRepairByZone()/100, // Средний % рем. работ
                "=(F$row+G$row)/2", // Средний % рем. работ в целом
                $lateDate, // Крайняя дата окончания рем. работ
                $user_info->getCurator(),


            ];
            $sheet->fromArray($other_arr, null, 'E'.$row, true);

            $sheet->getRowDimension($index+1)->setRowHeight(65);
//            $index++;

            foreach ($curency_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
            }

            $spreadsheet->getActiveSheet()->getStyle("O$row:P$row")
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);


            //===================================


            $sheet = $spreadsheet->getSheetByName('Оборудование');

            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $total = $procentage['furniture'] + $procentage['PO'] + $procentage['equipment'];


            $user_info_arr = [
                $user_info->getRfSubject()->getName(),
                $user_info->getDeclaredIndustry(),
                $user_info->getEducationalOrganization(),
                $zoneCount,
                $total > 0 ? (
                        $procentage['furniture_fact'] +
                        $procentage['PO_fact'] +
                        $procentage['equipment_fact'] +
                        $procentage['allowance_fact']
                    )/$total : '0',     // Средний % укомплектованности МТБ
                $total > 0 ? (
                        $procentage['furniture_put'] +
                        $procentage['PO_put'] +
                        $procentage['equipment_put'] +
                        $procentage['allowance_put']
                    )/$total : '0',  // Средний % введения в эксплуатацию
                $user->getEquipmentDeliveryDeadline(), // Крайняя дата завершения комплектования
                $status ? $status->getStatus() : '', // Статус проверки
                $status ? $status->getComment() : '', // Комментарии куратора
                $user_info->getCurator() // Куратор
            ];

            $sheet->fromArray($user_info_arr, "-", 'B'.$row, true);

            foreach ($proc_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
            }

            $sheet->getRowDimension($index+1)->setRowHeight(65);
            $index++;
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

        $sheet = $spreadsheet->getSheetByName('Оборудование');
        $rangeTotal = 'A2:K'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:Q')->getAlignment()->setVertical('center');



        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $rangeTotal = 'A2:J'.$end_cell;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:V')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:V')->getAlignment()->setVertical('center');


        // Запись файла
        $writer = new Xlsx($spreadsheet);

        if($save)
        {
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
            }

            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);

            return $fileName;
        }
        else{
//            $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function downloadTableBas(int $year, $save = false, $start=0, $step = 200)
    {

//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('readiness_map_table_bas_template_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $today = new \DateTime('now');
        $index = 1;
        $users = $this->getUsersByYearPaginator($year, '%ROLE_BAS%', $start, $step);




        foreach ($users as $user)
        {
            $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
            $adresses = $user->getClusterAddresses();

            $user_info = $user->getUserInfo();

            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);
            $user_info_arr = [
                $user_info->getRfSubject()->getName(),

            ];
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);
            $procent_cell = ['D', 'E', 'F'];
            foreach ($adresses as $adress)
            {


                $arr = [
                    $adress->getAddresses(),
                    $adress->getMidRepairByZone()/100,
                    $adress->getMidRepairByCommon()/100,
                    "=(D$row+E$row)/2",
                    $adress->getDeadlineForCompletionOfRepairs() ? $adress->getDeadlineForCompletionOfRepairs()->format('d.m.Y') : "",
                ];

                foreach ($procent_cell as $cell)
                {
                    $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
                }
                $sheet->fromArray($arr, null, 'C'.$row, true);
                $row++;
            }


            $sheet->getRowDimension($index+1)->setRowHeight(65);
//            $index++;


            $spreadsheet->getActiveSheet()->getStyle("O$row:P$row")
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);

            //-------------------------------------------------------------
            //---------------------ОБОРУДОВАНИЕ----------------------------
            //-------------------------------------------------------------
            $sheet = $spreadsheet->getSheetByName('Оборудование');
            $row = $sheet->getHighestRow()+1;
            $check = $user->getReadinessMapChecks()->last();
            if($check)
            {
                $status = $check->getStatus()->last();

            }
            else
            {
                $status = false;
            }
            $user_info = $user->getUserInfo();
            $user_info_arr = [
                $index,
                $user_info->getRfSubject()->getName(),

            ];
            $sheet->fromArray($user_info_arr, null, 'A'.$row, true);
            $procent_cell = ['D', 'E', 'F'];
            foreach ($adresses as $adress) {
                $eq = $adress->getCountOfEquipment();

                $arr = [
                    $adress->getAddresses(),
                    $eq['total'] ? $eq['fact']/$eq['total'] : 0,
                    $adress->getEquipmentDeliveryDeadline() ? $adress->getEquipmentDeliveryDeadline()->format('d.m.Y') : '',
                    $status ? $status->getStatus() : '', // Статус проверки
                    $status ? $status->getComment() : '', // Комментарии куратора
                    $user_info->getCurator() // Куратор
                ];

                $sheet->fromArray($arr, null, 'C'.$row, true);
                foreach ($procent_cell as $cell)
                {
                    $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
                }
                $row++;


            }

            $index++;
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
        foreach (['Оборудование', 'Ремонтные работы'] as $sheetName)
        {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $end_cell = $sheet->getHighestRow();
            $rangeTotal = 'A2:H'.$end_cell;
            $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
            $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A:Q')->getAlignment()->setVertical('center');
        }

        // Запись файла
        $writer = new Xlsx($spreadsheet);
        $fileName = 'БАС';
        if($save)
        {
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
            }

            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);

            return $fileName;
        }
        else{
//            $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function downloadTableEquipment(int $year, string $role = 'cluster', $save = false, $start=0, $step = 10)
    {
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

//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('readiness_map_table_template_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);


        $sheet = $spreadsheet->getSheetByName('Оборудование');
        $index = 1;
        $proc_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'K'];
        if($role != 'cluster')
        {
            $sheet->insertNewColumnBefore('M');
            $sheet->getStyle('M1')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'font' => [
                    'size'  => 12,
                    'name'  => 'Times New Roman',
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '70ad47']
                ]
            ])->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension('M')->setWidth(20);
            $sheet->setCellValue('M1', 'Средний % укомплектованности учебными пособиями');
            $proc_cell = ['F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'K', 'O'];
        }
        $today = new \DateTime('now');
        $index = 1;




        if($role == 'lot_1')
        {
            $fileName = 'Карта готовности лот 1 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%ROLE_SMALL_CLUSTERS_LOT_1%', $start, $step);
        }

        else if($role == 'lot_2')
        {
            $fileName = 'Карта готовности лот 2 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%ROLE_SMALL_CLUSTERS_LOT_2%', $start, $step);
        }

        else
        {
            $fileName = 'Карта готовности ОПЦ '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYearPaginator($year, '%REGION%', $start, $step);
        }


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

                'allowance' => 0,
                'allowance_fact' => 0,
                'allowance_put' => 0,
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
//                        $procentage['allowance_mid'] += ($arr['allowance'] > 0) ? ($arr['allowance_fact'] / $arr['allowance']) * 100 : 0;

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

                        $procentage['allowance'] += $arr['allowance'];
                        $procentage['allowance_fact'] += $arr['allowance_fact'];
                        $procentage['allowance_put'] += $arr['allowance_put'];
                    }
                }
            }
            $user_info = $user->getUserInfo();
            $row = $sheet->getHighestRow()+1;
            $sheet->setCellValue('A'.$row, $index);

            $count = 0;
            if($procentage['furniture'] > 0)
                $count++;
            if($procentage['PO'] > 0)
                $count++;
            if($procentage['equipment'])
                $count++;
            if($procentage['allowance'])
                $count++;

            if($role != 'cluster')
                $user_info_arr = [
                    $user_info->getRfSubject()->getName(),
                    $user_info->getDeclaredIndustry(),
                    $user_info->getEducationalOrganization(),
                    $zoneCount,
                    round($procentage['F'], 2)/100,
                    round($procentage['G'], 2)/100,
                    round($procentage['H'], 2)/100,
                    round($procentage['I'], 2)/100,
                    $this->midleProc($procentage['furniture'], $procentage['furniture_fact']),
                    $this->midleProc($procentage['PO'], $procentage['PO_fact']),
                    $this->midleProc($procentage['equipment'], $procentage['equipment_fact']),
                    $this->midleProc($procentage['allowance'], $procentage['allowance_fact']),
                    "=Sum(J$row:M$row)/$count",
                    ($count > 0) ? ($this->midleProc($procentage['furniture'], $procentage['furniture_put'])+
                            $this->midleProc($procentage['PO'], $procentage['PO_put'])+
                            $this->midleProc($procentage['equipment'], $procentage['equipment_put'])+
                            $this->midleProc($procentage['allowance'], $procentage['allowance_put'])
                        )/$count : 0,
                    '-',
                    '-',
                    '-',
                    '-',
                    $user->getEquipmentDeliveryDeadline(),
                    '-',
                    '',
                    $user_info->getCurator()

                ];
            else
                $user_info_arr = [
                    $user_info->getRfSubject()->getName(),
                    $user_info->getDeclaredIndustry(),
                    $user_info->getEducationalOrganization(),
                    $zoneCount,
                    round($procentage['F'], 2)/100,
                    round($procentage['G'], 2)/100,
                    round($procentage['H'], 2)/100,
                    round($procentage['I'], 2)/100,
                    $this->midleProc($procentage['furniture'], $procentage['furniture_fact']),
                    $this->midleProc($procentage['PO'], $procentage['PO_fact']),
                    $this->midleProc($procentage['equipment'], $procentage['equipment_fact']),
                    "=Sum(J$row:L$row)/$count",
                    ($count > 0) ? ($this->midleProc($procentage['furniture'], $procentage['furniture_put'])+
                    $this->midleProc($procentage['PO'], $procentage['PO_put'])+
                    $this->midleProc($procentage['equipment'], $procentage['equipment_put']))/$count : 0,
                    '-',
                    '-',
                    '-',
                    '-',
                    $user->getEquipmentDeliveryDeadline(),
                    '-',
                    '',
                    $user_info->getCurator()

                ];
            $sheet->fromArray($user_info_arr, "-", 'B'.$row, true);

            foreach ($proc_cell as $cell)
            {
                $sheet->getStyle("$cell$row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);
            }

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

        if($save)
        {
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
            }

            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);

            return $fileName;
        }
        else{
//            $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function downloadTableRepair(int $year, string $role = 'cluster', $save = false)
    {
//        $sheet_template = "../public/excel/readinessMap.xlsx";
        $sheet_template = $this->getParameter('readiness_map_table_template_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getSheetByName('Ремонтные работы');
        $today = new \DateTime('now');
        $index = 1;


        if($role == 'lot_1')
        {
            $fileName = 'Карта готовности лот 1 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_1%');
        }

        else if($role == 'lot_2')
        {
            $fileName = 'Карта готовности лот 2 '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%ROLE_SMALL_CLUSTERS_LOT_2%');
        }

        else
        {
            $fileName = 'Карта готовности ОПЦ '.$year." год ".$today->format('d-m-Y');
            $users = $this->getUsersByYear($year, '%REGION%');
        }


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
            $sheet->fromArray($user_info_arr, null, 'B'.$row, true);
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
            $sheet->fromArray($other_arr, null, 'E'.$row, true);

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
        $sheet->removeColumn('E');




        // Запись файла
        $writer = new Xlsx($spreadsheet);

        if($save)
        {
            $fileName = $fileName.'_'.uniqid().'.xlsx';
            if (!file_exists($this->getParameter('readiness_map_saves_directory'))) {
                mkdir($this->getParameter('readiness_map_saves_directory'), 0777, true);
            }

            $writer->save($this->getParameter('readiness_map_saves_directory').'/'.$fileName);

            return $fileName;
        }
        else{
//            $fileName = 'Карта готовности_'.$today->format('d-m-Y').'.xlsx';
            $fileName = $fileName.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);


            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

    }
    public function midleProc($total, $fact)
    {
        if($total > 0){
            $result = $fact / $total;

            if($result > 0)
                return $result;
            else
                return 0;
        }
        else
        {
            return 0;
        }
    }

}