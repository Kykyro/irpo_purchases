<?php

namespace App\Controller\Admin;

use App\Services\XlsxAllPurchasesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPurchasesController extends AbstractController
{
    /**
     * @Route("/admin/purchases", name="app_admin_purchases")
     */
    public function index(): Response
    {
        return $this->render('admin/admin_purchases/index.html.twig', [
            'controller_name' => 'AdminPurchasesController',
        ]);
    }

    /**
     * @Route("/admin/purchases-download", name="app_admin_purchases_download")
     */
    public function  downloadPurchases(Request $request, XlsxAllPurchasesService $service)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');
        $role = $request->request->get('role');

        if ($this->isCsrfTokenValid('admin_purchases_download', $submittedToken)) {
            return $service->download($year,$role);
        }


    }
}
