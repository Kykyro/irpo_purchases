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
        return $this->file('../public/files/Создание_дизайн_проекта_Профессионалитет_2023.pdf');
    }
    /**
     * @Route("/region-is-download/{file}", name="app_download_region_is_file")
     */
    public function RegionISDownload(string $file): Response
    {
        return $this->file('../public/uploads/infrastructureSheetsRegion/'.$file);
    }
    /**
     * @Route("/design-project-presentation-download/{file}", name="app_download_design_project_presentation_file")
     */
    public function DesignProjectPresentationDownload(string $file): Response
    {
        return $this->file('../public/uploads/designProjectExamplePresentation/'.$file);
    }

    /**
     * @Route("/download-file/{route}/{file}", name="app_download_file_by_route")
     */
    public function fileDownloadByRoute(string $file, string $route): Response
    {
        $path = $this->getParameter($route);
        return $this->file($path.'/'.$file);
    }
    /**
     * @Route("/download-file/{route}", name="app_download_file_by_only_route")
     */
    public function fileDownloadByOnlyRoute( string $route): Response
    {
        $path = $this->getParameter($route);
        return $this->file($path);
    }
}
