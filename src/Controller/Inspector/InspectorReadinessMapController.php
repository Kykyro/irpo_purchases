<?php

namespace App\Controller\Inspector;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspector")
 */
class InspectorReadinessMapController extends AbstractController
{
    /**
     * @Route("/readiness-map", name="app_inspector_readiness_map")
     */
    public function index(): Response
    {
        return $this->render('inspector_readiness_map/index.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
        ]);
    }
}
