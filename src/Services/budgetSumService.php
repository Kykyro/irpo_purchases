<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class budgetSumService extends AbstractController
{

    public function getInitialBudget(array $dump, \DateTimeImmutable $day)
    {
        $sum = [
            'FederalFunds'  => 0,
            'FundsOfSubject' => 0,
            'EmployersFunds' => 0,
            'EducationalOrgFunds' => 0
        ];

        foreach ($dump as $item){
            $dateTime = new \DateTime("@{$day->setTime(0,0,0,0)->getTimeStamp()}");
            $status = $item->getPurchasesStatus($dateTime);
            if($status == 'announced')
            {
                $sum['FederalFunds'] += $item->getInitialFederalFunds();
                $sum['FundsOfSubject'] += $item->getInitialFundsOfSubject();
                $sum['EmployersFunds'] += $item->getInitialEmployersFunds();
                $sum['EducationalOrgFunds'] += $item->getInitialEducationalOrgFunds();
            }
        }

        return $sum;
    }

    public function getFinBudget(array $dump, \DateTimeImmutable $day)
    {

        $sum = [
            'FederalFunds'  => 0,
            'FundsOfSubject' => 0,
            'EmployersFunds' => 0,
            'EducationalOrgFunds' => 0
        ];

        foreach ($dump as $item){
            $dateTime = new \DateTime("@{$day->setTime(0,0,0,0)->getTimeStamp()}");
            $status = $item->getPurchasesStatus($dateTime);
            if($status == 'contract') {
                $sum['FederalFunds'] += $item->getFinFederalFunds();
                $sum['FundsOfSubject'] += $item->getFinFundsOfSubject();
                $sum['EmployersFunds'] += $item->getFinEmployersFunds();
                $sum['EducationalOrgFunds'] += $item->getFinFundsOfEducationalOrg();
            }
        }

        return $sum;
    }

}