<?php

namespace App\Controller\Admin;

use App\Services\clusterFinancing\ClusterFinancingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDownloadController extends AbstractController
{
    /**
     * @Route("/admin/download", name="app_admin_download")
     */
    public function index(): Response
    {
        return $this->render('admin/admin_download/index.html.twig', [
            'controller_name' => 'AdminDownloadController',
        ]);
    }

    /**
     * @Route("/admin/download/cluster-financing", name="app_admin_download_cluster_financing")
     */
    public function clusterFinancing(ClusterFinancingService $service): Response
    {
        return $service->generate();
    }
}
