<?php

namespace App\Controller\Admin;

use App\Entity\TotalBudget;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class TotalBudgetController extends AbstractController
{
    /**
     * @Route("/total-budget", name="app_total_budget")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $totalBudget = $entityManager->getRepository(TotalBudget::class)->findAll();
        $roleMask = [
            "ROLE_REGION" => 'Производственный кластер' ,
            "ROLE_SMALL_CLUSTER" => 'Малый кластер',
        ];
        return $this->render('total_budget/index.html.twig', [
            'controller_name' => 'TotalBudgetController',
            'totalBudget' => $totalBudget,
            'role_mask' => $roleMask,
        ]);
    }

    /**
     * @Route("/total-budget/add", name="app_total_budget_add")
     * @Route("/total-budget/edit/{id}", name="app_total_budget_edit")
     */
    public function add(EntityManagerInterface $entityManager, Request $request, int $id = null)
    {
        if($id)
        {
            $totalBudget = $entityManager->getRepository(TotalBudget::class)->find($id);
        }
        else{
            $totalBudget = new TotalBudget();
        }
        $form = $this->createFormBuilder($totalBudget)
            ->add('federal', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => 'ФБ',
            ])
            ->add('employeers', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => 'РД',
            ])
            ->add('subject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => 'СС',
            ])
            ->add('Edicational', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => 'ОО',
            ])
            ->add('year', ChoiceType::class, [
                'choices'  => [

                    '2021' => 2021,
                    '2022' => 2022,
                    '2023' => 2023,
                    '2024' => 2024,

                ],
                'required'   => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Год создания кластера'
            ])
            ->add('role', ChoiceType::class, [
                'choices'  => [

                    'Производственный кластер' => "ROLE_REGION",
                    'Малый кластер лот 1' => "ROLE_SMALL_CLUSTER_LOT_1",
                    'Малый кластер лот 2' => "ROLE_SMALL_CLUSTER_LOT_2",

                ],
                'required'   => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Роль'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success btn-lg'
                ],
                'label' => 'Создать'
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $entityManager->persist($totalBudget);
            $entityManager->flush();
            return $this->redirectToRoute("app_total_budget");


        }


        return $this->render('total_budget/add.html.twig', [
            'controller_name' => 'TotalBudgetController',
            'form' => $form->createView()
        ]);
    }
}
