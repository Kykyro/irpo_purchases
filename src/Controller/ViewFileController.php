<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/view")
 */
class ViewFileController extends AbstractController
{
    /**
     * @Route("/pdf/{file}", name="app_download_purchases_file")
     */
    public function pdf(string $file): Response
    {
        return http_response_code(404);
    }

//    /**
//     * @Route("/excel/{file}", name="app_download_purchases_file")
//     */
//    public function excel(): Response
//    {
//
//    }

}
