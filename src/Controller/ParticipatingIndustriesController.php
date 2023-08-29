<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipatingIndustriesController extends AbstractController
{
    /**
     * @Route("/", name="app_participating_industries")
     */
    public function index(): Response
    {
        return $this->render('start_landing/participatingIndustries', [
            'controller_name' => 'ParticipatingIndustriesController',
        ]);
    }
}
