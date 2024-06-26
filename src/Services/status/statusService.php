<?php

namespace App\Services\status;

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

class statusService extends AbstractController
{
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUsers($year, $role)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        return $entity_manager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'rf')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->setParameter('role', "%$role%")
            ->setParameter('year', "$year")
            ->orderBy('rf.name', 'ASC')
            ->getQuery()
            ->getResult();



    }
    public function getUserById($id){
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);
        return $user;

    }




    public function tableGenerator($year, $role, $tags)
    {

        $users = $this->em->getRepository(User::class)->getUsersByYearRoleTags($year, $role, $tags);
        $headerRow = [
            '№',
            'Субъект РФ',
            'Отрасль',
            'Базовая ОО',
            'Справка по закупкам',
            'Стасус карт готовности',
            'Куратор',
            'Роль'


        ];
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Состояние");
        $sheet->fromArray($headerRow);

        $index = 1;
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
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];



        foreach ($users as $user)
        {
            $row_index = $sheet->getHighestRow()+1;
            $sheet->fromArray($this->getRow($user, $index), null, 'A'.$row_index, true);

            $index++;
        }

        $rangeTotal = 'A1:H'.($sheet->getHighestRow());
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->setAutoFilter('A1:H1');
//        $sheet->refreshRowDimensions();

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);

        $roleNames = [
            'ROLE_SMALL_CLUSTERS_LOT_1' => 'СПО лот 1',
            'ROLE_SMALL_CLUSTERS_LOT_2' => 'СПО лот 2',
            'ROLE_SMALL_CLUSTERS' => 'СПО',
            'ROLE_REGION' => 'ОПЦ(К)',
        ];
        $today = new \DateTimeImmutable('now');
        $fileName = "Состояние КГ СК ".$roleNames[$role]." ".$year." ".$today->format('d-m-Y').".xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function getRow($user, $index){
        $user_info = $user->getUserInfo();

        if(in_array('ROLE_SMALL_CLUSTERS_LOT_1' ,$user->getRoles()))
            $_role = 'СПО лот 1';
        else if(in_array('ROLE_SMALL_CLUSTERS_LOT_2' ,$user->getRoles()))
            $_role = 'СПО лот 2';
        else if(in_array('ROLE_REGION' ,$user->getRoles()))
            $_role = 'ОПЦ(К)';
        else
            $_role = '?';

        if($user_info->getContractCertifications()->last())
            $purchases = $user_info->getContractCertifications()->last()->getStatus();
        else
            $purchases = '';


        if($user->getReadinessMapChecks()->last())
            if($user->getReadinessMapChecks()->last()->getStatus()->last())
                $readiness = $user->getReadinessMapChecks()->last()->getStatus()->last()->getStatus();
            else
                $readiness = '';
        else
            $readiness = '';

        $row = [
            $index,
            $user_info->getRfSubject()->getName(),
            $user_info->getDeclaredIndustry(),
            $user_info->getEducationalOrganization(),
            $purchases,
            $readiness,
            $user_info->getCurator(),
            $_role

        ];


        return $row;
    }


}