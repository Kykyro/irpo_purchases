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

class XlsxClusterIndustryService extends AbstractController
{
    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;
    }

    public function generate($year, $role)
    {
        $users = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->setParameter('year', $year)
            ->setParameter('role', "%$role%")
            ->orderBy('uf.rf_subject', 'ASC')
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
        $industrys = $this->getIndustres($users);
        $headerRow = [
            'Наименование субъекта Российской Федерации',

        ];
        $headerRow = array_merge($headerRow, $industrys);
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("sheet");
        $sheet->fromArray($headerRow);

        if(count($users))
        {
            dump($industrys);
            $region = $users[0]->getUserInfo()->getRfSubject()->getName();
            foreach ($users as $user)
            {
                if($region == $user->getUserInfo()->getRfSubject()->getName())
                {

                }
                else
                {
                    $region = $user->getUserInfo()->getRfSubject()->getName();
                }
                dump($user->getUserInfo()->getRfSubject()->getName());
                dump($user->getUserInfo()->getDeclaredIndustry());
                dump('--------');
            }
            dd();
        }


        $index = $sheet->getHighestRow()+1;
        $highestColumn = $sheet->getHighestColumn();
        $rangeTotal = 'A1:'.$highestColumn.$index;
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(50);


        // Create Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'excel.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
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
                ->andWhere('uf.id LIKE :id')
                ->setParameter('id', $userInfoId)
                ->getQuery()
                ->getResult()
                ;
            if(count($user))
            {
                $roles =  implode(" | ", $user[0]->getRoles());
                if(str_contains($roles, "ROLE_SMALL_CLUSTERS"))
                    return "Кластер СПО";
                else if(str_contains($roles, "ROLE_REGION"))
                    return "ОПЦ(К)";
                else
                    return "";

            }
            else{
                return "";
            }

        }
        else{
            return "";
        }
    }

    private function getIndustres($users)
    {
        $arr = [];
        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            array_push($arr, $user_info->getDeclaredIndustry());
        }

        return array_unique($arr);
    }
}