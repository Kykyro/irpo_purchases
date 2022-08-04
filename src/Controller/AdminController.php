<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/main", name="app_admin")
     */
    public function adminPanel(): Response
    {


        return $this->render('admin/base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
