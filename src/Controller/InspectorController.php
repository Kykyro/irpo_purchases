<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorController extends AbstractController
{
    /**
     * @Route("/main", name="app_inspector_main")
     */
    public function index(): Response
    {






        return $this->render('inspector/index.html.twig', [
            'controller_name' => 'InspectorController',
        ]);
    }
}
