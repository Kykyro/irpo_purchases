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
use Symfony\Component\Validator\Constraints\DateTime;

class XlsxAllPurchasesService extends AbstractController
{
    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;
    }

    public function download($year)
    {
        $users = $this->entity_manager
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role or u.roles LIKE :role2')
            ->andWhere('uf.year = :year')
            ->setParameter('year', $year)
            ->setParameter('role', "%ROLE_SMALL_CLUSTERS%")
            ->setParameter('role2', "%ROLE_REGION%")
            ->getQuery()
            ->getResult();

        return $this->generate($users, "Все закупки $year год.xlsx");
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
            'Базовая ОО',
            'Предмет закупки',
            'Способ определения поставщика',
            'ВСЕГО',

            'средства федерального бюджета',
            'средства субъекта РФ',
            'средства работодателей',
            'средства образовательной организации',

            'Статус закупки'
//            'Тип кластера'
        ];
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Закупки");
        $sheet->fromArray($headerRow);
        $count = 1;
        $statusLib = [
          'cancelled'  => 'Отменена',
          'planning'  => 'планируется',
          'contract'  => 'закантрактовано',
          'announced'  => 'Объявлено',
        ];
        $today = new \DateTime('now');
        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            $purcases = $this->entity_manager->getRepository(ProcurementProcedures::class)
                ->findByUser($user);

            foreach ($purcases as $purcase)
            {
                $row = [
                    $count,
                    $user_info->getEducationalOrganization(),
                    $purcase->getPurchaseObject(),
                    $purcase->getMethodOfDetermining(),
                    "",
                    $purcase->getInitialFederalFunds(),
                    $purcase->getInitialFundsOfSubject(),
                    $purcase->getInitialEmployersFunds(),
                    $purcase->getInitialEducationalOrgFunds(),
                    $statusLib[$purcase->getPurchasesStatus($today)],
                ];



                $sheet->fromArray($row, 0, 'A'.($count+1), true);

                $count++;

                $sheet->setCellValue("E$count", "=SUM(F$count:I$count)");
            }
        }
        $index = $sheet->getHighestRow()+1;
        $rangeTotal = 'A1:J'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getStyle("E2:I$index")->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
        $sheet->getStyle("A1:J1")->applyFromArray([
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => '77bc65')
            )
        ]);
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