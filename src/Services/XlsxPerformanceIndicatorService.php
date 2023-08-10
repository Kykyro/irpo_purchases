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

class XlsxPerformanceIndicatorService extends AbstractController
{


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

    public function generateTable($year, $role)
    {
        if($role == "lot_1")
        {

            $users = $this->getUsers($year, "ROLE_SMALL_CLUSTERS_LOT_1");
            $role = "ROLE_SMALL_CLUSTERS";
        }
        elseif($role == "lot_2")
        {

            $users = $this->getUsers($year, "ROLE_SMALL_CLUSTERS_LOT_2");
            $role = "ROLE_SMALL_CLUSTERS";
        }
        else
        {
            $users = $this->getUsers($year, $role);
        }


        return $this->tableGenerator($users, $year, $this->getDict($role, $year), $this->getTitle($role));
    }



    public function tableGenerator($users, $year, $dict, $title)
    {
        $sheet_template = "../public/excel/PerformanceIndicator.xlsx";

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
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
        $sheet->setCellValue("A1", $title);
        $yearCol = ['E', 'M', 'U'];
        $i = 0;
        foreach ($yearCol as $col)
        {
            $sheet->setCellValue($col."3", $year+$i);
            $sheet->fromArray($dict, null, $col."4");
            $i++;
        }


        foreach ($users as $user)
        {
            $row_index = $sheet->getHighestRow()+1;

            $sheet->fromArray($this->getRow($user, $index), "'0", 'A'.$row_index);
            $row_arr = ['K', 'L', 'S', 'T', 'AA', 'AB'];
            foreach ($row_arr as $j){
                $sheet->getStyle($j.$row_index)->getNumberFormat()->setFormatCode('#,##0.00_-"₽"');
            }
            $index++;
        }

        $rangeTotal = 'A6:AB'.($sheet->getHighestRow());
        $sheet->getStyle($rangeTotal)->applyFromArray($styleArray);
        $sheet->getStyle($rangeTotal)->getAlignment()->setWrapText(true);

        //write it again to Filesystem with the same name (=replace)
        $writer = new Xlsx($spreadsheet);

        $fileName = "Показатели результативности.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function getRow($user, $index){
        $user_info = $user->getUserInfo();
        $row = [
            $index,
            $user_info->getRfSubject()->getName(),
            $user_info->getDeclaredIndustry(),
            $user_info->getEducationalOrganization(),
            $user_info->getStudentsCount(),
            $user_info->getProgramCount(),
            $user_info->getTeacherCount(),
            $user_info->getWorkerCount(),
            $user_info->getStudentsCountWithMentor(),
            $user_info->getJobSecurityCount(),
            $user_info->getAmountOfFunding(),
            $user_info->getAmountOfExtraFunds(),
        ];
        $indecators = $user_info->getClusterPerfomanceIndicators();

        foreach ($indecators as $indecator)
        {
            $_row = [

                $indecator->getStudentCount(),
                $indecator->getProgramCount(),
                $indecator->getTeacherCount(),
                $indecator->getWorkerCount(),
                $indecator->getStudentCountWithMentor(),
                $indecator->getJobSecurityCount(),
                $indecator->getAmountOfFunding(),
                $indecator->getAmountOfExtraFunds(),
            ];
            $row = array_merge($row, $_row);
        }

        return $row;
    }

    private function getDict($role, $year){

        if ('ROLE_REGION' == "$role")
        {
            if($year == 2023)
            {
                return [
                    'studentsCount'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», в том числе разработанных с применением автоматизированных методов конструирования указанных образовательных программ',
                    'programCount'=> 'Количество реализуемых образовательных программ в интересах организаций реального сектора экономики',
                    'teacherCount'=> 'Количество педагогических работников, владеющих актуальными педагогическими, производственными (профильными), цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики',
                    'workerCount' => 'Количество работников организаций реального сектора экономики, владеющих актуальными педагогическими навыками, цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения по совместительству',
                    'studentsCountWithMentor'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе центра с закреплением наставника, работающего в организации реального сектора экономики',
                    'jobSecurityCount'=> 'Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанных в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'amountOfFunding'=> ' Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками центра, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания центра тыс.руб.',
                    'amountOfExtraFunds'=> 'Объем внебюджетных средств (включая стоимость безвозмездно переданного образовательным организациям, являющимся участниками центра, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых участниками центра из числа организаций, действующих в реальном секторе экономики, на развитие центра тыс.руб.',
                ];
            }
            elseif($year == 2024)
            {
                return [
                    'studentsCount'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'programCount'=> 'Количество реализуемых образовательных программ центра в интересах организаций реального сектора экономики, участвующих в реализации программы деятельности центра',
                    'teacherCount'=> 'Количество педагогических работников образовательных организаций, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения производственных навыков',
                    'workerCount' => 'Количество работников, участвующих в реализации программы деятельности центра, призеров и победителей чемпионатов профессионального мастерства, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения педагогических навыков',
                    'studentsCountWithMentor'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе центра с закреплением наставника, работающего в организации, участвующей в реализации программы деятельности центра',
                    'jobSecurityCount'=> 'Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным, в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'amountOfFunding'=> 'Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками центра, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания центра тыс.руб.',
                    'amountOfExtraFunds'=> 'Объем внебюджетных средств (включая стоимость безвозмездно переданного организациями, действующими в реальном секторе экономики и участвующими в реализации программы деятельности центра, образовательным организациям, являющимся участниками центра, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых на развитие центра тыс.руб.',
                ];
            }
            else
            {
                return [
                    'studentsCount'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», в том числе разработанных с применением автоматизированных методов конструирования указанных образовательных программ',
                    'programCount'=> 'Количество реализуемых образовательных программ в интересах организаций реального сектора экономики',
                    'teacherCount'=> 'Количество педагогических работников, владеющих актуальными педагогическими, производственными (профильными), цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики',
                    'workerCount' => 'Количество работников организаций реального сектора экономики, владеющих актуальными педагогическими навыками, цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения по совместительству',
                    'studentsCountWithMentor'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе центра с закреплением наставника, работающего в организации реального сектора экономики',
                    'jobSecurityCount'=> 'Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанных в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'amountOfFunding'=> ' Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками центра, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания центра тыс.руб.',
                    'amountOfExtraFunds'=> 'Объем внебюджетных средств (включая стоимость безвозмездно переданного образовательным организациям, являющимся участниками центра, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых участниками центра из числа организаций, действующих в реальном секторе экономики, на развитие центра тыс.руб.',
                ];
            }
        }
        elseif ('ROLE_SMALL_CLUSTERS' == "$role")
        {
            if($year == 2023)
            {
                return [
                    'studentsCount'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'programCount'=> 'Количество реализуемых образовательных программ образовательного кластера в интересах организаций, участвующих в реализации программы деятельности образовательного кластера',
                    'teacherCount'=> 'Количество педагогических работников образовательных организаций, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения производственных навыков',
                    'workerCount' => 'Количество работников, участвующих в реализации программы деятельности образовательного кластера, призеров и победителей чемпионатов профессионального мастерства, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения педагогических навыков',
                    'studentsCountWithMentor'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе образовательного кластера с закреплением наставника, работающего в организации, участвующей в реализации программы деятельности образовательного кластера',
                    'jobSecurityCount'=> 'Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'amountOfFunding'=> ' Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками образовательного кластера, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания образовательного кластера тыс.руб.',
                    'amountOfExtraFunds'=> 'Объем внебюджетных средств (включая стоимость безвозмездно переданного организациями, участвующими в реализации программы деятельности образовательного кластера, образовательным организациям, являющимся участниками образовательного кластера, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых на развитие образовательного кластера (при наличии) тыс.руб.',
                ];
            }
            elseif($year == 2024)
            {
                return [
                    'studentsCount'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'programCount'=> 'Количество реализуемых образовательных программ образовательного кластера в интересах организаций, участвующих в реализации программы деятельности образовательного кластера',
                    'teacherCount'=> 'Количество педагогических работников образовательных организаций, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения производственных навыков',
                    'workerCount' => 'Количество работников, участвующих в реализации программы деятельности образовательного кластера, призеров и победителей чемпионатов профессионального мастерства, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения педагогических навыков',
                    'studentsCountWithMentor'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе образовательного кластера с закреплением наставника, работающего в организации, участвующей в реализации программы деятельности образовательного кластера',
                    'jobSecurityCount'=> 'Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'amountOfFunding'=> 'Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками образовательного кластера, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания образовательного кластера тыс.руб.',
                    'amountOfExtraFunds'=> 'Объем внебюджетных средств (включая стоимость безвозмездно переданного организациями, участвующими в реализации программы деятельности образовательного кластера, образовательным организациям, являющимся участниками образовательного кластера, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых на развитие образовательного кластера (при наличии) тыс.руб.',
                ];
            }
            else{
                return [
                    'studentsCount'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'programCount'=> 'Количество реализуемых образовательных программ образовательного кластера в интересах организаций, участвующих в реализации программы деятельности образовательного кластера',
                    'teacherCount'=> 'Количество педагогических работников образовательных организаций, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения производственных навыков',
                    'workerCount' => 'Количество работников, участвующих в реализации программы деятельности образовательного кластера, призеров и победителей чемпионатов профессионального мастерства, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения, прошедших обучение по дополнительным профессиональным программам, в том числе с применением дистанционных образовательных технологий, обеспечивающих реализацию мероприятий федерального проекта «Профессионалитет», в том числе в части получения педагогических навыков',
                    'studentsCountWithMentor'=> 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе образовательного кластера с закреплением наставника, работающего в организации, участвующей в реализации программы деятельности образовательного кластера',
                    'jobSecurityCount'=> 'Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта «Профессионалитет», разработанным в том числе с применением автоматизированных методов конструирования указанных образовательных программ',
                    'amountOfFunding'=> ' Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками образовательного кластера, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания образовательного кластера тыс.руб.',
                    'amountOfExtraFunds'=> 'Объем внебюджетных средств (включая стоимость безвозмездно переданного организациями, участвующими в реализации программы деятельности образовательного кластера, образовательным организациям, являющимся участниками образовательного кластера, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых на развитие образовательного кластера (при наличии) тыс.руб.',
                ];
            }
        }


    }

    private function getTitle($role)
    {
        if ('ROLE_REGION' == "$role")
        {
            return "Приложение 1. Данные по показателям, указанным в Приложении 5, программ деятельности образовательно-производственных центров (кластеров) с уточнением значений  по каждому году реализации Проекта";
        }
        elseif ('ROLE_REGION' == "$role")
        {
            return "Приложение 1. Данные по показателям, указанным в Приложении 5, программ деятельности кластеров СПО с уточнением значений  по каждому году реализации Проекта";
        }
        else
        {
            return "Приложение 1. Данные по показателям, указанным в Приложении 5, программ деятельности образовательно-производственных центров (кластеров) с уточнением значений  по каждому году реализации Проекта";
        }
    }
}