<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/view")
 */
class ViewFileController extends AbstractController
{
    /**
     * @Route("/pdf/{pdfFilename}", name="app_download_purchases_file_pdf")
     */
    public function pdfAction($pdfFilename)
    {
        $path = '../public/'.$pdfFilename;
        $response = new BinaryFileResponse($path);

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE, //use ResponseHeaderBag::DISPOSITION_ATTACHMENT to save as an attachement
            $pdfFilename
        );

       return $response;
    }



}
