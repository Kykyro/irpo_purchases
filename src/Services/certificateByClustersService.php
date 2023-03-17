<?php

namespace App\Services;

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

class certificateByClustersService extends AbstractController
{


    public function __construct(SluggerInterface $slugger)
    {

    }

    public function getCertificate($users)
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');

        $templateProcessor = new TemplateProcessor('../public/word/Шаблон для заполнения справки.docx');
        $replacements = [];

        foreach ($users as $user)
        {
            $user_info = $user->getUserInfo();
            $templateData = [
                'rf_subject' => is_null($user_info->getRfSubject())  ? '' : $user_info->getRfSubject()->getName() ,
                'industry' => $user_info->getDeclaredIndustry(),
                'cluster_name' => $user_info->getCluster(),
                'grant_name' => $user_info->getOrganization(),
                'intiator_name' => $user_info->getInitiatorOfCreation(),
                'employers_count' => count($user_info->getListOfEmployers()),
                'employers' => implode("; ", $user_info->getListOfEmployers()),
                'web_oo_count' => count($user_info->getListOfEdicationOrganization()),
                'web_oo' => implode("; ", $user_info->getListOfEdicationOrganization()),
                'rf_subject_funds' => $fmt->format($user_info->getFinancingFundsOfSubject() * 1000),
                'economic_sector_funds' => $fmt->format($user_info->getExtraFundsEconomicSector() * 1000),
                'oo_funds' => $fmt->format($user_info->getExtraFundsOO() * 1000),
                'base_org' => $user_info->getEducationalOrganization(),
            ];

            array_push($replacements, $templateData);
        }

        $templateProcessor->cloneBlock('clusterInfo', 0, true, false, $replacements);


        $fileName = 'Справка_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }


}