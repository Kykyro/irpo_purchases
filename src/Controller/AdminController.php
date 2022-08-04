<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin")
     */
    public function adminPanel(): Response
    {

        /**
         * @Route("/main", name="app_admin")
         */
        return $this->render('admin/base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
