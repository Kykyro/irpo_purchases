<?php

namespace App\Controller;

use App\Services\InfrastructureSheetDownloadXlsxService;
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
            return $xlsxService->generate($year);
        }
        return $this->render('statistic_and_analytic/index.html.twig', [
            'controller_name' => 'StatisticAndAnalyticController',
            'form' => $form->createView(),
        ]);
    }
}
