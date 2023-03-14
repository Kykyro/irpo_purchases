<?php

namespace App\Services;

use App\Entity\ProcurementProcedures;
use App\Entity\User;
use NumberFormatter;
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
    public function generateSertificate($id)
    {
        $user = $this->getUserById($id);
        $userInfo = $user->getUserInfo();
        $today = new \DateTime('now');
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

        $procent['contractFedFunds'] = ($sum['contractFedFunds'] * 100)/(100000000);
        $procent['contractRegionFunds'] = ($sum['contractRegionFunds'] * 100)/($userInfo->getFinancingFundsOfSubject() * 1000);
        $procent['contractOOFunds'] = ($sum['contractOOFunds'] * 100)/($userInfo->getExtraFundsOO() * 1000);
        $procent['contractEmplFunds'] = ($sum['contractEmplFunds'] * 100)/($userInfo->getExtraFundsEconomicSector() * 1000);

        $procent['factEmplFunds'] = ($sum['factEmplFunds'] * 100)/($userInfo->getExtraFundsEconomicSector() * 1000);
        $procent['factFedFunds'] = ($sum['factFedFunds'] * 100)/(100000000);
        $procent['factOOFunds'] = ($sum['factOOFunds'] * 100)/($userInfo->getExtraFundsOO() * 1000);
        $procent['factRegionFunds'] = ($sum['factRegionFunds'] * 100)/($userInfo->getFinancingFundsOfSubject() * 1000);

        $templateProcessor = new TemplateProcessor('../public/word/Справка_о_контрактации_и_расходовании_средств.docx');
        $templateProcessor->setValues([
            'rf_subject' => $userInfo->getRfSubject()->getName(),
            'base_organization' => $userInfo->getOrganization(),
            'industry' => $userInfo->getDeclaredIndustry(),
            'date' => $today->format('d.m.Y'),
            'year' => $userInfo->getYear(),
            'ExtraFundsEconomicSector' => $fmt->format($userInfo->getExtraFundsEconomicSector() * 1000),
            'FinancingFundsOfSubject' => $fmt->format($userInfo->getFinancingFundsOfSubject() * 1000),
            'ExtraFundsOO' => $fmt->format($userInfo->getExtraFundsOO() * 1000),

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