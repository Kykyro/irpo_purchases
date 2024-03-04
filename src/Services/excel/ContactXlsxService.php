<?php

namespace App\Services\excel;

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

class ContactXlsxService extends AbstractController
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
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Содержимое ячейки А1');
        $sheet->setTitle("Это новый лист документа");

        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'excel_symfony4.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function generateContactTable(int $year, string $role)
    {
        $sheet_template = "../public/excel/Таблица контакты ОПЦ(к) и ОК СПО.xlsx";
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $users = $this->getUsers($role, $year);
        $sheet = $spreadsheet->getActiveSheet();

        $headerRow = [];
        $headerTempl = ['ФИО',	'Телефон',	'Почта'];
        foreach ($this->contactTypes as $type)
        {

            array_push($headerRow, $type->getName());
            foreach ($headerTempl as $tmpl)
                array_push($headerRow, $tmpl);
        }

        $sheet->fromArray($headerRow, '', 'R1');

        $index = 1;
        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            $contacts = $userInfo->getContactInfo();
            $row = [
                $index,
                '', // № заявки
                '', // № заявки из системы
                $userInfo->getRfSubject()->GetDistrict(), // Федеральный округ
                $userInfo->getRfSubject()->getName(), // Субъект РФ (базовой ОО)
                $userInfo->getDeclaredIndustry(),
                $userInfo->getCluster(),
                $userInfo->getInitiatorOfCreation(),
                $userInfo->getEducationalOrganization(),
                '', // Базовая образовательная организация кластера
                '', // Куратор
                '', // Замещающий куратор
                '', // Официальная почта кластера
            ];

            $sheet->fromArray($row, '', 'A'.($index+1));
            $sheet->fromArray($this->createContacts($contacts), '', 'N'.($index+1));
            $index++;
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
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
//        $index = $sheet->getHighestRow()+1;
        $rangeTotal = 'A2:BA'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'Контакты '.$year.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);
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

    public function createContacts($contact)
    {
        if(is_null($contact))
            return [];

        $director = $contact->getDirector();
        $responsibleContacts = $contact->getResponsibleContacts();
        $employers = $contact->getEmployersContacts();

        $row = [
            $director ? '' : '',
            $director ? $director->getFIO() : '',
            $director ? $director->getPhoneNumber() : '',
            $director ? $director->getEmail() : '',
        ];
        // отвественные
        foreach ($this->contactTypes as $type)
        {
            $a = ['', '', '', ''];
            foreach ($responsibleContacts as $contact)
            {
                if ($contact->getResponsibleContactTypes()->contains($type)) {
                    $a[0] = $a[0];
                    $a[1] = $a[1].$contact->getFIO()."\n\n";
                    $a[2] = $a[2].$contact->getPhoneNumber()."\n\n";
                    $a[3] = $a[3].$contact->getEmail()."\n\n";

                }
            }
            foreach ($a as $b)
            {
                array_push($row, $b);
            }
        }
        for($i = 0; $i < 8; $i++)
        {
            array_push($row, '');
        }
        $employersArr = ['', '', '', ''];
        // Работадатели
        foreach ($employers as $employer)
        {
            $employersArr[0] = $employersArr[0].$employer->getEmployer()->getName()."\n\n";
            $employersArr[1] = $employersArr[1].$employer->getFIO()."\n\n";
            $employersArr[2] = $employersArr[2].$employer->getPhoneNumber()."\n\n";
            $employersArr[3] = $employersArr[3].$employer->getEmail()."\n\n";
        }

        foreach ($employersArr as $arr)
        {
            array_push($row, $arr);
        }


        return $row;

    }


}