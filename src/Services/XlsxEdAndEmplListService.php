<?php

namespace App\Services;

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

class XlsxEdAndEmplListService extends AbstractController
{
    public function getClusters()
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $clusters = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('uf.year > :year')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%ROLE_REGION%")
            ->setParameter('year', 2021)
            ->orderBy('uf.year', 'ASC')
            ->getQuery()
            ->getResult();

        $arr = [];
        if(count($clusters) > 0)
        {
            foreach ($clusters as $cluster)
            {
                if(!array_key_exists($cluster->getUserInfo()->getYear(), $arr))
                {
                    $arr[$cluster->getUserInfo()->getYear()] = [];
                }

                array_push($arr[$cluster->getUserInfo()->getYear()], $cluster->getUserInfo());
            }
        }
        return $arr;
    }

    public function generate()
    {
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = new Spreadsheet();

        /** @var Worksheet $sheet */
//        $sheet = $spreadsheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Содержимое ячейки А1');
//        $sheet->setTitle("Это новый лист документа");
        $arr = $this->getClusters();
        foreach ($arr as $i)
        {
            $title = "{$i[0]->getYear()}";
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $title);
            $spreadsheet->addSheet($myWorkSheet);
        }
        $additSheets = ['Статистика', 'Список'];
        foreach ($additSheets as $i)
        {
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $i);
            $spreadsheet->addSheet($myWorkSheet);
        }


        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
        ;
        $totalArray = [
            'org' => [],
            'empl' => [],
            'baseOO' => []
        ];
        foreach ($arr as $i)
        {
            $title = "{$i[0]->getYear()}";
            $columnArrayOrg = [];
            $columnArrayEmpl = [];
            $columnArrayBaseOO = [];
            $titles = ['Базовые ОО','Образовательные организации', 'Работодатели'];

            foreach ($i as $user_info)
            {
                 foreach ($user_info->getListOfEdicationOrganization() as $org)
                 {
                     array_push($columnArrayOrg, $org);
                 }
                 foreach ($user_info->getListOfEmployers() as $empl)
                 {
                     array_push($columnArrayEmpl, $empl);
                 }
                 array_push($columnArrayBaseOO, $user_info->getEducationalOrganization());
            }
            $columnArrayOrg = array_unique($columnArrayOrg);
            $columnArrayEmpl = array_unique($columnArrayEmpl);

            array_push($totalArray['org'], $columnArrayOrg);
            array_push($totalArray['empl'], $columnArrayEmpl);
            array_push($totalArray['baseOO'], $columnArrayBaseOO);

            $columnArrayOrg = array_chunk($columnArrayOrg, 1);
            $columnArrayEmpl = array_chunk($columnArrayEmpl, 1);
            $columnArrayBaseOO = array_chunk($columnArrayBaseOO, 1);


            $spreadsheet->getSheetByName($title)
                ->fromArray(
                    $columnArrayBaseOO,   // The data to set
                    NULL,          // Array values with this value will not be set
                    'A2'            // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
                )->fromArray(
                    $columnArrayOrg,   // The data to set
                    NULL,          // Array values with this value will not be set
                    'B2'            // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
                )->fromArray(
                    $columnArrayEmpl,   // The data to set
                    NULL,          // Array values with this value will not be set
                    'C2'            // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
                )->fromArray(
                    $titles,   // The data to set
                    NULL,          // Array values with this value will not be set
                    'A1'            // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
                )

            ;
            $spreadsheet->getSheetByName($title)->getColumnDimension('A')->setAutoSize(TRUE);
            $spreadsheet->getSheetByName($title)->getColumnDimension('B')->setAutoSize(TRUE);
            $spreadsheet->getSheetByName($title)->getColumnDimension('C')->setAutoSize(TRUE);

        }


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

}