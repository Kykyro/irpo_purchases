<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/dowload")
 */
class DowloadController extends AbstractController
{
    /**
     * @Route("/purchasesFile/{file}", name="app_download_purchases_file")
     */
    public function index(string $file): Response
    {
        return $this->file('../public/uploads/purchasesFiles/'.$file);
    }
    /**
     * @Route("/infrastructure-sheet-download/{file}", name="app_download_purchases_file")
     */
    public function ISDownload(string $file): Response
    {
        return $this->file('../public/uploads/infrastructureSheetsFiles/'.$file);
    }
}
