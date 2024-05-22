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

class XlsxEmployersService extends AbstractController
{
    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;
    }

    public function generate()
    {
        $employers = $this->entity_manager
            ->getRepository(Employers::class)
            ->getEmployers();
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
            'Работодатель',
            'Наименование',
            'Сокращенное наименование',
            'Описание',
            'ОКВЭД',
            'ОКВЭД (Дополнительный)',
            'Категории',
            'Кластер',
            'Год',
            'Отрасль',
            'ИНН',
            'Область',
            'Город',
            'Тип кластера',
            'Регион',
            'Округ',
            'ОГРН',
            'Теги',
        ];
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Работадатели");
        $sheet->fromArray($headerRow);
        foreach ($employers as $employer)
        {
            $row_index = $sheet->getHighestRow()+1;
            $row = $employer->getAsRow();

            $sheet->fromArray($row, null, 'A'.$row_index);

            $sheet->setCellValue("N$row_index", $this->getRoles($employer));
            $sheet->setCellValue("R$row_index", $this->getTags($employer));
            $spreadsheet->getActiveSheet()->getStyle('K'.$row_index)->getNumberFormat()
                ->setFormatCode('@');
        }
        $index = $sheet->getHighestRow()+1;
        $rangeTotal = 'A1:R'.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(50);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(50);
        $sheet->getColumnDimension('J')->setWidth(50);
        $sheet->getColumnDimension('K')->setWidth(35);
        $sheet->getColumnDimension('L')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(30);
        $sheet->getColumnDimension('O')->setWidth(35);
        $sheet->getColumnDimension('P')->setWidth(35);
        $sheet->getColumnDimension('Q')->setWidth(35);
        $sheet->getColumnDimension('R')->setWidth(35);

        $sheet->setAutoFilter("A1:R1");
        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Работадатели.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function getTags($employer)
    {
        $userInfos = $employer->getUserInfos();
        $tags = [];
        foreach ($userInfos as $userInfo)
        {
            $user = $this->entity_manager->getRepository(User::class)->getUserByUserInfo($userInfo);

            $_tags = $user->getUserTagsArray();
            $tags = array_merge($tags, $_tags);
        }
        $tags = array_unique($tags);
        $str = "";
        $index = 1;

        foreach ($tags as $tag)
        {
            $str = $str.$index.") ".$tag."\n";
            $index++;
        }

        return $str;
    }

    private function getRoles($employer)
    {
        $userInfos = $employer->getUserInfos();
        if(count($userInfos))
        {
            $userInfoId = $userInfos[0]->getId();

            $user =  $this->entity_manager->getRepository(User::class)
                ->createQueryBuilder('u')
                ->leftJoin('u.user_info', 'uf')
                ->andWhere('uf.id IN (:id)')
                ->setParameter('id', $userInfos)
                ->getQuery()
                ->getResult()
                ;
            if(count($user))
            {
                $result = [];
                foreach ($user as $u)
                {
                    $roles =  implode(" | ", $u->getRoles());
                    if(str_contains($roles, "ROLE_SMALL_CLUSTERS"))
                        array_push($result, "Кластер СПО");
                    else if(str_contains($roles, "ROLE_REGION"))
                        array_push($result, "ОПЦ(К)");
                }
                sort($result);
                $result = array_unique($result);
                $result = implode(" / ", $result);

                return $result;

            }
            else{
                return "";
            }

        }
        else{
            return "";
        }
    }
}