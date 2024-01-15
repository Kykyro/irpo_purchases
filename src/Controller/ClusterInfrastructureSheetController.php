<?php

namespace App\Controller;

use App\Form\InfrastructureSheets\infrastructureSheetForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $data = [
            'infrastructureSheets' => [
                0 => [
                    'name' => 'aaaa'
                ]
            ]
        ];
        $form = $this->createForm(infrastructureSheetForm::class, $data);




        return $this->render('cluster_infrastructure_sheet/edit.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
            'form' => $form->createView(),
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

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование зоны:',

            ])
            ->add('numberOfWorkplaces', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Кол-во рабочих мест:',
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {




            return $this->redirectToRoute('app_cluster_infrastructure_sheet');
        }

        return $this->render('cluster_infrastructure_sheet/edit_zone.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
            'form' => $form->createView(),
        ]);
    }
}
