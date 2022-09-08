<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StartLandingController extends AbstractController
{
    /**
     * @Route("/", name="app_start_landing")
     */
    public function index(): Response
    {
        return $this->render('start_landing/index.html.twig', [
            'controller_name' => 'StartLandingController',
        ]);
    }
}
