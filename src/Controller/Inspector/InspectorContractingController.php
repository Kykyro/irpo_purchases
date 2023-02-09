<?php

namespace App\Controller\Inspector;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class InspectorContractingController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorContractingController extends AbstractController
{
    /**
     * @Route("/contracting", name="app_inspector_contracting")
     */
    public function index(): Response
    {
        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
        ]);
    }
}
