<?php

namespace App\Controller\Inspector;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/inspector")
 */
class CuratorProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_curator_profile")
     */
    public function index(): Response
    {
        return $this->render('curator_profile/index.html.twig', [
            'controller_name' => 'CuratorProfileController',
        ]);
    }
}
