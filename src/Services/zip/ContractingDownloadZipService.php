<?php

namespace App\Services\zip;

use App\Entity\User;
use App\Services\XlsxService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use ZipArchive;

class ContractingDownloadZipService extends AbstractController
{

    private $em;
    private $xlsxService;

    public function __construct(EntityManagerInterface $entityManager, XlsxService $xlsxService)
    {
        $this->em = $entityManager;
        $this->xlsxService = $xlsxService;
    }

    public function download($year, $role)
    {

        $dir = $this->getParameter('repair_photos_directory');
        $fileName = "file.zip";
        $users = $this->em->getRepository(User::class)->findByYearAndRole($year, $role);
        $files = [];
        $filesNames = [];


        foreach ($users as $user)
        {
            $userId = $user->getId();
            $userInfo = $user->getUserInfo();
            $rfSubject = $userInfo->getRfSubject();


            if('ROLE_BAS' == $role)
            {
                array_push($files, $this->xlsxService->generatePurchasesProcedureTableBas($userId));
                array_push($filesNames, $rfSubject->getName()." ".$userInfo->getYear().".xlsx");
            }

            else
            {
                array_push($files, $this->xlsxService->generatePurchasesProcedureTable($userId));
                array_push($filesNames, $userInfo->getEducationalOrganization()." ".$userInfo->getYear().".xlsx");
            }

        }

        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $zip = new ZipArchive();

        $zip->open($temp_file, \ZipArchive::CREATE);

        for ($i = 0; $i < count($files); $i++)
        {
            $temp_table = $files[$i]->getFile()->getRealpath();
            $zip->addFile($temp_table, $filesNames[$i]);

        }

        $status = $zip->close();


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

}