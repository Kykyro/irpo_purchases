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
     * @Route("/infrastructure-sheet-download/{file}", name="app_download_infrastructure_sheet_file")
     */
    public function ISDownload(string $file): Response
    {
        return $this->file('../public/uploads/infrastructureSheetsFiles/'.$file);
    }
    /**
     * @Route("/document-download/{file}", name="app_download_document_file")
     */
    public function DocumentDownload(string $file): Response
    {
        return $this->file('../public/uploads/documentsFiles/'.$file);
    }
    /**
     * @Route("/article-file-download/{file}", name="app_download_article_file")
     */
    public function ArticleFileDownload(string $file): Response
    {
        return $this->file('../public/uploads/articleFiles/'.$file);
    }
    /**
     * @Route("/brandbook-download/", name="app_download_brandbook")
     */
    public function BrandbookDownload(): Response
    {
        return $this->file('../public/files/Brand Book 2.0 professionalitet_8+3 отрасли_060922_.pdf');
    }
    /**
     * @Route("/memo-file-download/", name="app_download_memo_file")
     */
    public function MemoFileDownload(): Response
    {
        return $this->file('../public/files/Памятка_по_созданию_дизайн_проектов_кластеров.pdf');
    }
}
