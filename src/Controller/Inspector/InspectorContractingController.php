<?php

namespace App\Controller\Inspector;

use App\Entity\ContractingTables;
use App\Entity\ProcurementProcedures;
use App\Entity\User;
use App\Services\ContractingXlsxService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class InspectorContractingController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorContractingController extends AbstractController
{
    /**
     * @Route("/contracting-actual", name="app_inspector_contracting")
     */
    public function index(Request $request, ContractingXlsxService $contractingXlsxService): Response
    {

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    '2021 год' => 2021,
                    '2023 год' => 2023,

                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Скачать'
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $contractingXlsxService->downloadTable($data['year']);

        }

        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/contracting-history", name="app_inspector_contracting_history")
     */
    public function history(Request $request, ContractingXlsxService $contractingXlsxService): Response
    {

       $contractingTables = $this->getDoctrine()->getManager()
           ->getRepository(ContractingTables::class)
           ->findAll();




        return $this->render('inspector_contracting/history.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'contractingTables' => $contractingTables
        ]);
    }
}
