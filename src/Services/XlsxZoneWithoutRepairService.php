<?php

namespace App\Services;

use App\Entity\Employers;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchasesDump;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Entity\ZoneRepair;
use App\Repository\ClusterZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class XlsxZoneWithoutRepairService extends AbstractController
{
    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;
    }

    public function download($year, $role)
    {
        $users = $this->entity_manager
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('u.clusterAddresses', 'a')
            ->leftJoin('a.clusterZones', 'z')
            ->leftJoin('z.zoneRepair', 'zr')

            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->andWhere('zr.notPlanned = :notPlanned')

            ->setParameter('notPlanned', true)
            ->setParameter('year', $year)
            ->setParameter('role', "%$role%")

            ->getQuery()
            ->getResult();

        return $this->generate($users, 'Зоны без ремонта.xlsx');
    }

    public function downloadActualRepair($year, $role)
    {
        $users = $this->entity_manager
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('u.clusterAddresses', 'a')
            ->leftJoin('a.clusterZones', 'z')
            ->leftJoin('z.zoneRepair', 'zr')

            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')


            ->setParameter('year', $year)
            ->setParameter('role', "%$role%")

            ->getQuery()
            ->getResult();

        return $this->generate($users, 'Актуальный ремонт.xlsx');
    }


    public function generate($users, $fileName)
    {

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
            '№ п/п',
            'Регион',
            'Отрасль',
            'Базовая ОО',
            'Адрес',
            'Зона под вид работ',
            '% выполнения ремонтных работ',
            'Дата загрузки последних фотографий'
        ];
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Работадатели");
        $sheet->fromArray($headerRow);
        foreach ($users as $user)
        {
            $row_index = $sheet->getHighestRow()+1;
            $row_start = $sheet->getHighestRow()+1;
            $userInfo = $user->getUserInfo();
            $row = [
                $row_index,
                $userInfo->getRfSubject()->getName(),
                $userInfo->getDeclaredIndustry(),
                $userInfo->getEducationalOrganization(),

            ];
            $sheet->fromArray($row, null, 'A'.$row_index);
            $addresses = $user->getClusterAddresses();
            foreach ($addresses as $address)
            {
                $addressCount = 0;
                $zones = $address->getSortedClusterZones();
                foreach ($zones as $zone)
                {
                    $repair = $zone->getZoneRepair();

                    $addressCount++;
                    $photoDate = $repair->getPhotosVersions()->last();
                    $row = [
                        $address->getAddresses(),
                        $zone->getName(),
                        $repair->getTotalPercentage()*0.01,
                        $photoDate ? $photoDate->getCreatedAt()->format('d.m.Y') : '',

                    ];
                    $sheet->fromArray($row, null, 'E'.$row_index);
                    $row_index++;

                }
                if($addressCount>1)
                {
                    $sc = $row_index - $addressCount ;
                    $ec = $row_index - 1;
                    $sheet->mergeCells("E$sc:E$ec");
                }
            }
            if($row_start != $row_index)
            {
                $row_index--;
                $sheet->mergeCells("A$row_start:A$row_index");
                $sheet->mergeCells("A$row_start:A$row_index");
                $sheet->mergeCells("B$row_start:B$row_index");
                $sheet->mergeCells("C$row_start:C$row_index");
                $sheet->mergeCells("D$row_start:D$row_index");
            }

        }
        $index = $sheet->getHighestRow()+1;
        $rangeTotal = 'A1:H'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(150);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getStyle("G2:G$index")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_0);

        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system

        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}