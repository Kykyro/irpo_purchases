<?php

namespace App\Controller\Region;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/region")
 */
class RegionReadinessMapController extends AbstractController
{
    /**
     * @Route("/readiness-map", name="app_region_readiness_map")
     */
    public function index(): Response
    {
        return $this->render('region_readiness_map/index.html.twig', [
            'controller_name' => 'RegionReadinessMapController',
        ]);
    }
}
