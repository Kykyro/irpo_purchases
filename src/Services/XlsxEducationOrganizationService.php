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

class XlsxEducationOrganizationService extends AbstractController
{
    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;
    }

    public function generate($year)
    {
        $users = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role or a.roles LIKE :role2')
            ->andWhere('uf.year = :year')
            ->setParameter('role', "%REGION%")
            ->setParameter('role2', "%SMALL_CLUSTERS%")
            ->setParameter('year', $year)
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
        $headerRow = [
            '№п/п',
            'Наименование',
            'Кластер',
            'Тип кластера',
//            'Сокращенное наименование',
//            'Описание',
//            'ОКВЭД',
//            'ОКВЭД (Дополнительный)',
//            'Категории',
//
//            'Год',
//            'Отрасль',
//            'ИНН',
//            'Область',
//            'Город',
//
//            'Регион',
        ];
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Работадатели");
        $sheet->fromArray($headerRow);
        $count = 1;
        $organization = [];
        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            foreach ($userInfo->getListOfEdicationOrganization() as $edu)
            {
                array_push($organization, $edu);

                $row_index = $sheet->getHighestRow()+1;
                $row = [
                    $count,
                    $edu,
                    $userInfo->getEducationalOrganization(),
                    $this->getRoles($user),
                ];

                $sheet->fromArray($row, null, 'A'.$row_index);
                $count++;
            }

        }
        $index = $sheet->getHighestRow()+1;
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);

        $lastRowTitle = ['Всего (примерно): '];
        $sheet->fromArray($lastRowTitle, null, 'F1');

        $organization = array_unique($organization);
        $lastRow = [count($organization)];
        $sheet->fromArray($lastRow, null, 'G1');

        $rangeTotal = 'A1:D'.($index);
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Образовательные организации.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function getRoles($user)
    {
        $roles =  implode(" | ", $user->getRoles());
        if(str_contains($roles, "ROLE_SMALL_CLUSTERS"))
            return "Кластер СПО";
        else if(str_contains($roles, "ROLE_REGION"))
            return "ОПЦ(К)";

        return null;
    }
}