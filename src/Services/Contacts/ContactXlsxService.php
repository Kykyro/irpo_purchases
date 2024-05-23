<?php

namespace App\Services\Contacts;

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

class ContactXlsxService extends AbstractController
{

    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function generate()
    {

        $sheet_template = "../public/excel/Шаблон для контактов.xlsx";
        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $users = $this->em->getRepository(User::class)->findAllCluster();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($users as $user)
        {
            $userInfo = $user->getUserInfo();
            $rfSubject = $userInfo->getRfSubject();

            $row1 = [
                $user->getRoleString(),
                $userInfo->getYear(),
                $rfSubject->getDistrict(),
                $rfSubject->getName(),
                $userInfo->getDeclaredIndustry(),
                $userInfo->getEducationalOrganization(),
                $userInfo->getOrganization(),
                $userInfo->getCurator(),
            ];

            foreach ($user->getClusterContacts() as $contact)
            {
                $nextRow = $sheet->getHighestRow()+1;
                $row2 = [
                    $contact->getName(),
                    $contact->getPost(),
                    $contact->getPhoneNumber(),
                    $contact->getAddPhoneNumber(),
                ];

                $sheet->fromArray($row1, '', 'A'.$nextRow, true);
                $sheet->fromArray($row2, '', 'I'.$nextRow, true);

            }
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
        $index = $sheet->getHighestRow()+1;
        $rangeTotal = 'A2:L'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);

        $fileName = 'Таблица контактов.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);


    }



}