<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\User;
use NumberFormatter;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class monitoringReadinessMapService extends AbstractController
{


    public function __construct(SluggerInterface $slugger)
    {

    }

    public function getCertificate(User $user, $save = false, $file = '../public/word/Заключение_по_мониторинговому_выезду.docx')
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');
        $user_info = $user->getUserInfo();
        $addresses = $user->getClusterAddresses();
        if(is_null($addresses))
        {
            return false;
        }

        if(is_null($today))
            $today = new \DateTime('now');


        if(in_array('ROLE_REGION', $user->getRoles()))
        {
//            $title = 'Справка о контрактации и расходовании средств в рамках оснащения образовательно-производственного центра (кластера)';
            $grant = 100000000;
            $isSmolClustre = false;
        }
        elseif (in_array('ROLE_SMALL_CLUSTERS_LOT_1', $user->getRoles()))
        {
//            $title = 'Справка о контрактации и расходовании средств в рамках оснащения образовательного кластера среднего профессионального образования';
            $grant = 70000000;
            $isSmolClustre = true;
        }
        elseif (in_array('ROLE_SMALL_CLUSTERS_LOT_2', $user->getRoles()))
        {
//            $title = 'Справка о контрактации и расходовании средств в рамках оснащения образовательного кластера среднего профессионального образования';
            $grant = 60500000;
            $isSmolClustre = true;
        }
        else{
            throw new Exception('Ошибка роли');
        }

        $templateProcessor = new TemplateProcessor($file);


        $purchases = $this->getProcProc($user);

        if(is_null($user_info->getCertificateFunds()))
        {
            $declArr = [
                'subject' => '______ руб.',
                'industry' => '______ руб.',
                'OO' => '______ руб.',
            ];

        }
        else
        {
            $certificateFunds = $user_info->getCertificateFunds();
            $declArr = [
                'subject' => $fmt->format($certificateFunds->getSubjectFunds()),
                'industry' => $fmt->format($certificateFunds->getEconomicFunds()),
                'OO' => $fmt->format($certificateFunds->getExtraFunds()),
            ];


//            $certificateFunds->setEconomicFunds($data['ExtraFundsEconomicSector']);
//            $certificateFunds->setSubjectFunds($data['FinancingFundsOfSubject']);
//            $certificateFunds->setExtraFunds($data['ExtraFundsOO']);
        }

        $sum = [
            'contractFedFunds'=>0,
            'contractRegionFunds'=>0,
            'contractOOFunds'=>0,
            'contractEmplFunds'=>0,
            'factEmplFunds'=>0,
            'factFedFunds'=>0,
            'factOOFunds'=>0,
            'factRegionFunds'=>0,
        ];
        foreach ($purchases as $purchase)
        {
            if($purchase->getPurchasesStatus($today) === 'contract')
            {
                $sum['contractFedFunds'] += $purchase->getFinFederalFunds();
                $sum['contractRegionFunds'] += $purchase->getFinFundsOfSubject();
                $sum['contractOOFunds'] += $purchase->getFinFundsOfEducationalOrg();
                $sum['contractEmplFunds'] += $purchase->getFinEmployersFunds();

                $sum['factEmplFunds'] += $purchase->getFactEmployersFunds();
                $sum['factFedFunds'] += $purchase->getFactFederalFunds();
                $sum['factOOFunds'] += $purchase->getFactFundsOfEducationalOrg();
                $sum['factRegionFunds'] += $purchase->getFactFundsOfSubject();
            }
        }

        // Единичные замены
        $replacements = [
            'cluster_name' => $user_info->getCluster(),
            'cluster_base' => $user_info->getEducationalOrganization(),
            'industry' => $user_info->getDeclaredIndustry(),
            'rf_subject' => $user_info->getRfSubject()->getName(),

            // Закантрактованно
            'cFedFunds' => $fmt->format($sum['contractFedFunds']),
            'cEmplFunds' => $fmt->format($sum['contractEmplFunds']),
            'cRegionFunds' => $fmt->format($sum['contractRegionFunds']),
            'cOOFunds' => $fmt->format($sum['contractOOFunds']),
            // Израсходовано
            'fFedFunds' => $fmt->format($sum['factFedFunds']),
            'fEmplFunds' => $fmt->format($sum['factEmplFunds']),
            'fRegionFunds' => $fmt->format($sum['factRegionFunds']),
            'fOOFunds' => $fmt->format($sum['factOOFunds']),
            // фин. соглашение
            'grant' => $fmt->format($grant),
            'ExtraFundsEconomicSector' => $fmt->format($user_info->getExtraFundsEconomicSector() * 1000),
            'FinancingFundsOfSubject' => $fmt->format($user_info->getFinancingFundsOfSubject() * 1000),
            'ExtraFundsOO' => $fmt->format($user_info->getExtraFundsOO() * 1000),
            // Заявлено
            'ExtraFundsEconomicSector_d' => $declArr["subject"],
            'FinancingFundsOfSubject_d' => $declArr["subject"],
            'ExtraFundsOO_d' => $declArr["OO"],
            // Замена на тип кластера
            'cluster_type_1' => $isSmolClustre ? 'образовательного кластера среднего профессионального образования' : 'образовательно-производственного центра (кластера)',
            'cluster_type_2' => $isSmolClustre ? 'образовательных кластеров среднего профессионального образования' : 'образовательно-производственных центров (кластеров)',


        ];
        $templateProcessor->setValues($replacements);

        // Общие зоны
//        dd(count($addresses));
        $templateProcessor->cloneBlock('common_zone', count($addresses), true, true);
        $count_zones = 1;
//        $comment = '
//В наличии/отсутствует (причины);</w:t><w:br/><w:t xml:space="preserve">Введено/не введено в эксплуатацию(причины);</w:t><w:br/><w:t xml:space="preserve">В поставке (договор/контракт № __ от __ г., срок поставки до __ г.);</w:t><w:br/><w:t xml:space="preserve">В закупочных процедурах (номер закупки, дата завершения закупочных процедур, планируемая дата заключения договора/контракта, планируемая дата поставки и др.)</w:t><w:br/><w:t xml:space="preserve">Комментарии:
//        ';
        $comment = '
Ремонт завершен/не завершен </w:t><w:br/><w:t xml:space="preserve">Брендирование присутствует/отсутствует </w:t><w:br/><w:t xml:space="preserve">Дизайн-проекту и брендбуку соответствует/не соответствует </w:t><w:br/><w:t xml:space="preserve">(Комментарии: ремонт на стадии отделочных работ, брендирование отсутствует/частично присутствует и др.)';
        foreach ($addresses as $address)
        {
            $templateProcessor->setValue('address#'.$count_zones, $address->getAddresses());

            $values = [];
            $is_count = 1;
            foreach ($address->getSortedClusterZones() as $zone)
            {
                $repair = $zone->getZoneRepair();
                $arr = [
                    'is_num#'.$count_zones => $is_count,

                    'name#'.$count_zones => $zone->getName(),
                    'repair#'.$count_zones => $repair->getTotalPercentage(),
                    'end_date#'.$count_zones => is_null($repair->getEndDate()) ? "" : $repair->getEndDate()->format('d.m.Y') ,
                    'comment#'.$count_zones => $is_count == 1 ? $comment : '',

                ];
                array_push($values, $arr);
                $is_count++;
            }
            $templateProcessor->cloneRowAndSetValues('is_num#'.$count_zones, $values);
            $count_zones++;
        }


        // Зоны с ИЛами
        $zones = $user->getSortedWorkZones();

        $templateProcessor->cloneBlock('zone', count($zones), true, true);

        $count_zones = 1;
        $comment = '
В наличии/отсутствует (причины); </w:t><w:br/><w:t xml:space="preserve">Введено/не введено в эксплуатацию(причины); </w:t><w:br/><w:t xml:space="preserve">На складе в коробках (причины) ФОТО; </w:t><w:br/><w:t xml:space="preserve">В поставке (договор/контракт от даты, номер договора, сроки поставки по договору, СКАН договора/контракта и др.); </w:t><w:br/><w:t xml:space="preserve">В закупочных процедурах (номер закупки, дата завершения закупочных процедур, планируемая дата заключения договора/контракта, планируемая дата поставки и др.) </w:t><w:br/><w:t xml:space="preserve">Комментарии:
            ';
        foreach ($zones as $zone)
        {
            $placeCount = $zone->getPlaceCount()." рабочих мест";
            if ($zone->getPlaceCount() % 10 == 1 and $zone->getPlaceCount() != 11)
            {
                $placeCount = $zone->getPlaceCount()." рабочее место";
            }
            else if (
                $zone->getPlaceCount() % 10 > 1
                and $zone->getPlaceCount() % 10 < 5
                and (
                    $zone->getPlaceCount() > 20
                    or $zone->getPlaceCount() > 1 and $zone->getPlaceCount() < 5)
            )
            {
                $placeCount = $zone->getPlaceCount()." рабочих места";
            }

            $templateProcessor->setValue('zone_name#'.$count_zones, $zone->getName());
            $templateProcessor->setValue('place_count#'.$count_zones, $placeCount);

            $types = [
                'Общая зона' => 'is_num_o#',
//                'Рабочее место учащегося' => 'is_num_s#',
//                'Рабочее место преподавателя' => 'is_num_t#',
            ];

            $is_count = 1;
            $is_count_s = 1;
            $is_count_t = 1;
            $values = [];
            $values_s = [];
            $values_t = [];

            foreach ($zone->getZoneInfrastructureSheets() as $sheet)
            {
                if($sheet->getZoneType() == 'Общая зона')
                {
                    $arr = [
                        'is_num_o#'.$count_zones => $is_count,
                        'name#'.$count_zones => $sheet->getName(),
                        'type#'.$count_zones => $sheet->getType(),
                        'count#'.$count_zones => $sheet->getTotalNumber(),
                        'funds#'.$count_zones => is_null($sheet->getFunds()) ? "" : implode(", ", $sheet->getFunds()),
                        'comment#'.$count_zones => $is_count == 1 ? $comment : '',

                    ];
                    array_push($values, $arr);
                    $is_count++;
                }
                if($sheet->getZoneType() == 'Рабочее место учащегося')
                {
                    $arr = [
                        'is_num_s#'.$count_zones => $is_count_s,
                        'name#'.$count_zones => $sheet->getName(),
                        'type#'.$count_zones => $sheet->getType(),
                        'count#'.$count_zones => $sheet->getTotalNumber(),
                        'funds#'.$count_zones => is_null($sheet->getFunds()) ? "" : implode(", ", $sheet->getFunds()),
                        'comment#'.$count_zones => $is_count_s == 1 ? $comment : '',

                    ];
                    array_push($values_s, $arr);
                    $is_count_s++;
                }
                if($sheet->getZoneType() == 'Рабочее место преподавателя')
                {
                    $arr = [
                        'is_num_t#'.$count_zones => $is_count_t,
                        'name#'.$count_zones => $sheet->getName(),
                        'type#'.$count_zones => $sheet->getType(),
                        'count#'.$count_zones => $sheet->getTotalNumber(),
                        'funds#'.$count_zones => is_null($sheet->getFunds()) ? "" : implode(", ", $sheet->getFunds()),
                        'comment#'.$count_zones => $is_count_t == 1 ? $comment : '',

                    ];
                    array_push($values_t, $arr);
                    $is_count_t++;
                }


            }
            $templateProcessor->cloneRowAndSetValues('is_num_o#'.$count_zones, $values);
            $templateProcessor->cloneRowAndSetValues('is_num_s#'.$count_zones, $values_s);
            $templateProcessor->cloneRowAndSetValues('is_num_t#'.$count_zones, $values_t);
            $count_zones++;

        }

        $fileName = 'мониторинг_'.
            $user->getUserInfo()->getEducationalOrganization().
            '_'.$today->format('d.m.Y').
            '.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function getProcProc($user)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        return  $entity_manager->getRepository(ProcurementProcedures::class)
            ->createQueryBuilder('pp')
            ->andWhere('pp.user = :user')
            ->andWhere('pp.isDeleted = :isDeleted')
            ->setParameter('user', $user)
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult()
            ;
    }


}