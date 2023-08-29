<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\User;
use Exception;
use NumberFormatter;
use PhpOffice\PhpWord\TemplateProcessor;
use function PHPUnit\Framework\throwException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class certificateOfContractingService extends AbstractController
{


    public function getUserById($id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        return  $entity_manager->getRepository(User::class)->find($id);
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
    public function generateSertificate($id, $today = null)
    {
        $user = $this->getUserById($id);

        $userInfo = $user->getUserInfo();
        if(is_null($today))
            $today = new \DateTime('now');


        if(in_array('ROLE_REGION', $user->getRoles()))
        {
            $title = 'Справка о контрактации и расходовании средств в рамках оснащения образовательно-производственного центра (кластера)';
            $grant = 100000000;
        }
        elseif (in_array('ROLE_SMALL_CLUSTERS_LOT_1', $user->getRoles()))
        {
            $title = 'Справка о контрактации и расходовании средств в рамках оснащения образовательного кластера среднего профессионального образования';
            $grant = 70000000;
        }
        elseif (in_array('ROLE_SMALL_CLUSTERS_LOT_2', $user->getRoles()))
        {
            $title = 'Справка о контрактации и расходовании средств в рамках оснащения образовательного кластера среднего профессионального образования';
            $grant = 60500000;
        }
        else{
            throw new Exception('Ошибка роли');
        }


        $purchases = $this->getProcProc($user);
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, ' ');
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

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
        $procent = [
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

        $procent['contractFedFunds'] = ($sum['contractFedFunds'] * 100)/($grant);
        $procent['contractRegionFunds'] = ($userInfo->getFinancingFundsOfSubject() > 0) ? ($sum['contractRegionFunds'] * 100)/($userInfo->getFinancingFundsOfSubject() * 1000) : 0;
        $procent['contractOOFunds'] = ($userInfo->getExtraFundsOO() > 0) ? ($sum['contractOOFunds'] * 100)/($userInfo->getExtraFundsOO() * 1000) : 0;
        $procent['contractEmplFunds'] = ($userInfo->getExtraFundsEconomicSector() > 0) ? ($sum['contractEmplFunds'] * 100)/($userInfo->getExtraFundsEconomicSector() * 1000) : 0;

        $procent['factFedFunds'] = ($sum['factFedFunds'] * 100)/($grant);
        $procent['factEmplFunds'] = ($userInfo->getExtraFundsEconomicSector() > 0) ? ($sum['factEmplFunds'] * 100)/($userInfo->getExtraFundsEconomicSector() * 1000) : 0;
        $procent['factOOFunds'] = ($userInfo->getExtraFundsOO() > 0) ? ($sum['factOOFunds'] * 100)/($userInfo->getExtraFundsOO() * 1000) : 0;
        $procent['factRegionFunds'] = ($userInfo->getFinancingFundsOfSubject() > 0) ? ($sum['factRegionFunds'] * 100)/($userInfo->getFinancingFundsOfSubject() * 1000) : 0;

        $templateProcessor = new TemplateProcessor('../public/word/Справка_о_контрактации_и_расходовании_средств.docx');

        if($userInfo->getExtraFundsEconomicSector() > 0) {
            $templateProcessor->cloneBlock('ExtraFundsEconomicSector_BLOCK_1', 1);
            $templateProcessor->cloneBlock('ExtraFundsEconomicSector_BLOCK_2', 0);
        } else {
            $templateProcessor->cloneBlock('ExtraFundsEconomicSector_BLOCK_2', 1);
            $templateProcessor->cloneBlock('ExtraFundsEconomicSector_BLOCK_1', 0);
        }

        if($userInfo->getFinancingFundsOfSubject() > 0) {
            $templateProcessor->cloneBlock('FinancingFundsOfSubject_BLOCK_1', 1);
            $templateProcessor->cloneBlock('FinancingFundsOfSubject_BLOCK_2', 0);
        } else {
            $templateProcessor->cloneBlock('FinancingFundsOfSubject_BLOCK_2', 1);
            $templateProcessor->cloneBlock('FinancingFundsOfSubject_BLOCK_1', 0);
        }

        if($userInfo->getExtraFundsOO() > 0) {
            $templateProcessor->cloneBlock('ExtraFundsOO_BLOCK_1', 1);
            $templateProcessor->cloneBlock('ExtraFundsOO_BLOCK_2', 0);
        } else {
            $templateProcessor->cloneBlock('ExtraFundsOO_BLOCK_2', 1);
            $templateProcessor->cloneBlock('ExtraFundsOO_BLOCK_1', 0);
        }

        $templateProcessor->setValues([
            'title' => $title,
            'rf_subject' => $userInfo->getRfSubject()->getName(),
            'base_organization' => $userInfo->getOrganization(),
            'industry' => $userInfo->getDeclaredIndustry(),
            'date' => $today->format('d.m.Y'),
            'year' => $userInfo->getYear(),
            'ExtraFundsEconomicSector' => $fmt->format($userInfo->getExtraFundsEconomicSector() * 1000),
            'FinancingFundsOfSubject' => $fmt->format($userInfo->getFinancingFundsOfSubject() * 1000),
            'ExtraFundsOO' => $fmt->format($userInfo->getExtraFundsOO() * 1000),
            'GrantFunds' => $fmt->format($grant),

            'cFedFunds' => $fmt->format($sum['contractFedFunds']),
            'cEmplFunds' => $fmt->format($sum['contractEmplFunds']),
            'cRegionFunds' => $fmt->format($sum['contractRegionFunds']),
            'cOOFunds' => $fmt->format($sum['contractOOFunds']),
            'pcFedFunds' => $fmt->format($procent['contractFedFunds']),
            'pcEmplFunds' => $fmt->format($procent['contractEmplFunds']),
            'pcRegionFunds' => $fmt->format($procent['contractRegionFunds']),
            'pcOOFunds' => $fmt->format($procent['contractOOFunds']),

            'fFedFunds' => $fmt->format($sum['factFedFunds']),
            'fEmplFunds' => $fmt->format($sum['factEmplFunds']),
            'fRegionFunds' => $fmt->format($sum['factRegionFunds']),
            'fOOFunds' => $fmt->format($sum['factOOFunds']),
            'pfFedFunds' => $fmt->format($procent['factFedFunds']),
            'pfEmplFunds' => $fmt->format($procent['factEmplFunds']),
            'pfRegionFunds' => $fmt->format($procent['factRegionFunds']),
            'pfOOFunds' => $fmt->format($procent['factOOFunds']),
        ]);

        $fileName = $userInfo->getOrganization().'_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}