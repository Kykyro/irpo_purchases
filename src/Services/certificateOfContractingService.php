<?php

namespace App\Services;

use App\Entity\User;
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

    public function generateSertificate($id)
    {
        $user = $this->getUserById($id);
        $userInfo = $user->getUserInfo();
        $today = new \DateTime('now');


        $templateProcessor = new TemplateProcessor('../public/word/Справка_о_контрактации_и_расходовании_средств.docx');
        $templateProcessor->setValues([
            'rf_subject' => $userInfo->getRfSubject()->getName(),
            'base_organization' => $userInfo->getOrganization(),
            'industry' => $userInfo->getDeclaredIndustry(),
            'date' => $today->format('d.m.Y'),
            'year' => $userInfo->getYear(),
            'ExtraFundsEconomicSector' => $userInfo->getExtraFundsEconomicSector() * 1000,
            'FinancingFundsOfSubject' => $userInfo->getFinancingFundsOfSubject() * 1000,
            'ExtraFundsOO' => $userInfo->getExtraFundsOO() * 1000,
        ]);

        $fileName = $userInfo->getOrganization().'_'.$today->format('d.m.Y').'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}