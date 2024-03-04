<?php

namespace App\Controller;

use App\Services\excel\ContactXlsxService;
use App\Services\InfrastructureSheetDownloadXlsxService;
use App\Services\XlsxClusterIndustryService;
use App\Services\XlsxEducationOrganizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticAndAnalyticController extends AbstractController
{
    /**
     * @Route("/statistic-and-analytic", name="app_statistic_and_analytic")
     */
    public function index(Request $request, InfrastructureSheetDownloadXlsxService $xlsxService): Response
    {

        $form = $this->createFormBuilder()
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    '2021 год' => 2021,
                    '2022 год' => 2022,
                    '2023 год' => 2023,
                    '2024 год' => 2024,
                    '2025 год' => 2025,

                ],
                'label' => 'Год',
            ])
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'Большие кластеры' => 'ROLE_REGION',
                    'Малые кластеры (Лот 1)' => 'ROLE_SMALL_CLUSTERS_LOT_1',
                    'Малые кластеры (Лот 2)' => 'ROLE_SMALL_CLUSTERS_LOT_2',

                ],
                'multiple' => false,
                'expanded' => true,
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Скачать'
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $year = $data['year'];
            $role = $data['role'];
            return $xlsxService->generate($year, $role);
        }
        return $this->render('statistic_and_analytic/index.html.twig', [
            'controller_name' => 'StatisticAndAnalyticController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/statistic-and-analytic/Count-Cluster-Industry", name="app_statistic_and_analytic_cluster_count_industry")
     */
    public function getCountClusterIndustry(Request $request, XlsxClusterIndustryService $service)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');
        $role = $request->request->get('role');

        if ($this->isCsrfTokenValid('cluster_count_industry', $submittedToken)) {
            return $service->generate($year, $role);
        }
    }

    /**
     * @Route("/statistic-and-analytic/Count-edu-orgs", name="app_statistic_and_analytic_cluster_edu_orgs")
     */
    public function getCountEduOrgs(Request $request, XlsxEducationOrganizationService $service)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');


        if ($this->isCsrfTokenValid('cluster_edu_orgs', $submittedToken)) {
            return $service->generate($year);
        }
    }
    /**
     * @Route("/statistic-and-analytic/contacts", name="app_statistic_and_analytic_cluster_contacts")
     */
    public function getContacts(Request $request, ContactXlsxService $service)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');
        $role = $request->request->get('type');


        if ($this->isCsrfTokenValid('cluster_contact', $submittedToken)) {
            return $service->generateContactTable($year, $role);
        }
    }


}
