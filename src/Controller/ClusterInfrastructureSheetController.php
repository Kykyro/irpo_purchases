<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClusterInfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/region/infrastructure-sheet/edit/{id}", name="app_cluster_infrastructure_sheet_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, int $id): Response
    {
        return $this->render('cluster_infrastructure_sheet/edit.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
        ]);
    }
    /**
     * @Route("/region/infrastructure-sheet/edit-requirements/{id}", name="app_cluster_infrastructure_sheet_edit_requirements")
     */
    public function editRequirements(Request $request, EntityManagerInterface $em, int $id): Response
    {
        return $this->render('cluster_infrastructure_sheet/edit_requirements.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
        ]);
    }
    /**
     * @Route("/region/infrastructure-sheet", name="app_cluster_infrastructure_sheet")
     */
    public function index(): Response
    {
        return $this->render('cluster_infrastructure_sheet/index.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
        ]);
    }

    /**
     * @Route("/region/add-zone", name="app_cluster_infrastructure_sheet_add_zone")
     * @Route("/region/edit-zone/{id}", name="app_cluster_infrastructure_sheet_edit_zone")
     */
    public function editZone(Request $request, EntityManagerInterface $em, int $id = null): Response
    {
        return $this->render('cluster_infrastructure_sheet/edit_zone.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
        ]);
    }
}
