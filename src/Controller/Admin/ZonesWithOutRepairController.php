<?php

namespace App\Controller\Admin;

use App\Services\XlsxZoneWithoutRepairService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class ZonesWithOutRepairController extends AbstractController
{
    /**
     * @Route("/zones-with-out-repair", name="app_zones_with_out_repair")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        return $this->render('admin/zones_with_out_repair/index.html.twig', [
            'controller_name' => 'ZonesWithOutRepairController',
        ]);
    }
    /**
     * @Route("/zones-with-out-repair-download", name="app_zones_with_out_repair_get_table")
     */
    public function download(Request $request, XlsxZoneWithoutRepairService $repairService): Response
    {
        $year = $request->request->get('year');
        $role = $request->request->get('role');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('download', $submittedToken)) {
            return $repairService->download($year, $role);
        }
        return $this->redirectToRoute('app_zones_with_out_repair');

    }

}
