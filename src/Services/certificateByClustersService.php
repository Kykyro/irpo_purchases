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
        $replacementsWithGrand = [];
        $replacements2022 = [];

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
                'employers' => implode(", ", $user_info->getListOfEmployers()),
                'web_oo_count' => count($user_info->getListOfEdicationOrganization()),
                'web_oo' => implode(", ", $user_info->getListOfEdicationOrganization()),
                'rf_subject_funds' => $fmt->format($user_info->getFinancingFundsOfSubject() * 1000),
                'economic_sector_funds' => $fmt->format($user_info->getExtraFundsEconomicSector() * 1000),
                'oo_funds' => $fmt->format($user_info->getExtraFundsOO() * 1000),
                'base_org' => $user_info->getEducationalOrganization(),
            ];
            if($user_info->getYear() === 2022)
                array_push($replacements2022, $templateData);
            else
                if(strtolower($user_info->getEducationalOrganization()) == strtolower($user_info->getOrganization()))
                    array_push($replacements, $templateData);
                else
                    array_push($replacementsWithGrand, $templateData);

        }



        if(count($replacements) > 0)
        {
            $templateProcessor->cloneBlock('clusterInfo', 0, true, false, $replacements);
            $templateProcessor->setValue('new_title', '2023 год');
        }

        else
        {
            $templateProcessor->cloneBlock('clusterInfo', 0, true, false);
            $templateProcessor->setValue('new_title', '');
        }

        if(count($replacements2022) > 0)
        {
            $templateProcessor->cloneBlock('old', 0, true, false, $replacements2022);
            $templateProcessor->setValue('old_title', '2022 год');
        }

        else
        {
            $templateProcessor->cloneBlock('old', 0, true, false);
            $templateProcessor->setValue('old_title', '');
        }

        if(count($replacementsWithGrand) > 0)
        {
            $templateProcessor->cloneBlock('WithGrant', 0, true, false, $replacementsWithGrand);
            $templateProcessor->setValue('new_title', '2023 год');
        }
        else
        {
            $templateProcessor->cloneBlock('WithGrant', 0, true, false);
            $templateProcessor->setValue('new_title', '');
        }





        $fileName = 'Справка_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }


}