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

    public function getCertificate_2($users)
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

    public function getCertificate($users, $ugps = false, $zone = false)
    {
        $today = new \DateTime('now');
        $fmt = new NumberFormatter( 'ru_RU', NumberFormatter::CURRENCY );
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'руб.');

        $templateProcessor = new TemplateProcessor('../public/word/Шаблон для заполнения справки_2.docx');
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
                'employers' => implode(", ", $user_info->getListOfEmployers()),
                'web_oo_count' => count($user_info->getListOfEdicationOrganization()),
                'web_oo' => implode(", ", $user_info->getListOfEdicationOrganization()),
                'rf_subject_funds' => $fmt->format($user_info->getFinancingFundsOfSubject() * 1000),
                'economic_sector_funds' => $fmt->format($user_info->getExtraFundsEconomicSector() * 1000),
                'oo_funds' => $fmt->format($user_info->getExtraFundsOO() * 1000),
                'base_org' => $user_info->getEducationalOrganization(),
                'year' => $user_info->getYear(),
                'zone' => is_null($user_info->getZone()) ? [] : $user_info->getZone(),
                'ugps' => is_null($user_info->getUGPS()) ? [] : $user_info->getUGPS(),

            ];
            array_push($replacements, $templateData);
        }

        $templateProcessor->cloneBlock('clusterInfo', count($replacements), true, true);
        $count = 1;
        foreach ($replacements as $replacement)
        {
            $templateProcessor->setValues(
                [
                    'rf_subject#'.$count => $replacement['rf_subject'],
                    'industry#'.$count => $replacement['industry'],
                    'cluster_name#'.$count => $replacement['cluster_name'],
                    'intiator_name#'.$count => $replacement['intiator_name'],
                    'employers_count#'.$count => $replacement['employers_count'],
                    'employers#'.$count => $replacement['employers'],
                    'rf_subject_funds#'.$count => $replacement['rf_subject_funds'],
                    'economic_sector_funds#'.$count => $replacement['economic_sector_funds'],
                ]
            );
            if(strtolower($replacement['grant_name']) == strtolower($replacement['base_org']))
            {
                $templateProcessor->cloneBlock('with_grant#'.$count, 0, true, false);
                $templateProcessor->cloneBlock('without_grant#'.$count, 1, true, false);
                $templateProcessor->setValues([
                    'base_org#'.$count => $replacement['base_org'],
                ]);
            }
            else{
                $templateProcessor->cloneBlock('with_grant#'.$count, 1, true, false);
                $templateProcessor->cloneBlock('without_grant#'.$count, 0, true, false);

                $templateProcessor->setValues([
                    'grant_name#'.$count => $replacement['grant_name'],
                    'base_org#'.$count => $replacement['base_org'],
                ]);
            }
            if($replacement['web_oo_count'] > 0)
            {
                $templateProcessor->cloneBlock('with_oo#'.$count, 1, true, false);
                $templateProcessor->setValues([
                    'web_oo_count#'.$count => $replacement['web_oo_count'],
                    'web_oo#'.$count => $replacement['web_oo'],
                ]);
            }
            else{
                $templateProcessor->cloneBlock('with_oo#'.$count, 0, true, false);
            }
            if ($replacement['year'] > 2022)
            {
                $templateProcessor->cloneBlock('with_oo_funds#'.$count, 1, true, false);
                $templateProcessor->setValues([
                    'oo_funds#'.$count => $replacement['oo_funds'],

                ]);
            }
            else
            {
                $templateProcessor->cloneBlock('with_oo_funds#'.$count, 0, true, false);
            }

            if($ugps and count($replacement['ugps']) > 0)
            {
                $templateProcessor->cloneBlock('ugps_block#'.$count, 1, true, false);
                $_ugps_str = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', implode("\n", $replacement['ugps']) );
                $templateProcessor->setValue('ugps#'.$count, $_ugps_str);

            }
            else
            {
                $templateProcessor->cloneBlock('ugps_block#'.$count, 0, true, false);
            }

            if($zone and count($replacement['zone']) > 0)
            {
                $templateProcessor->cloneBlock('zone_block#'.$count, 1, true, false);
                $_zone_str = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', implode("\n", $replacement['zone']) );
                $templateProcessor->setValue('zone#'.$count, $_zone_str);
//                $templateProcessor->replaceBlock('zone#'.$count, implode('\n', $replacement['zone']));
            }
            else
            {
                $templateProcessor->cloneBlock('zone_block#'.$count, 0, true, false);
            }



            $count++;
        }



        $fileName = 'Справка_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

}