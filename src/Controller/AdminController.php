<?php

namespace App\Controller;

use http\Client\Curl\User;
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
        $entity_manager = $this->getDoctrine()->getManager();
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();

        return $this->render('admin/base.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
        ]);
    }
}
