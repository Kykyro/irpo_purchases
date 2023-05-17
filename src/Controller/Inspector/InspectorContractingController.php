<?php

namespace App\Controller\Inspector;

use App\Entity\ContractingTables;
use App\Entity\ProcurementProcedures;
use App\Entity\User;
use App\Services\ContractingXlsxService;
use App\Services\ReadinessMapXlsxService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
    public function index(Request $request, ContractingXlsxService $contractingXlsxService, ReadinessMapXlsxService $readinessMapXlsxService): Response
    {

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    '2021 год' => 2021,
                    '2022 год' => 2022,
                    '2023 год' => 2023,

                ],
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    'Контрактация' => 1,
                    'Карта готовности(ремонт)' => 2,

                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Скачать'
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required'   => false,
                'label' => 'Дата проверки'
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if($data['type'] == 1)
            {
                if($data['date'])
                {
                    return $contractingXlsxService->downloadTable($data['year'], $data['date']);
                }
                $today = new \DateTime('now');
                return $contractingXlsxService->downloadTable($data['year'], $today);
            }
            if($data['type'] == 2)
            {
                return $readinessMapXlsxService->downloadTable($data['year']);
            }


        }

        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'form' => $form->createView(),
            'role' => ''
        ]);
    }
    /**
     * @Route("/contracting-history", name="app_inspector_contracting_history")
     */
    public function history(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $dict = [
            'cluster'=> 'Контрактация (Производственные кластеры)' ,
            'lot_1' => 'Контрактация (Малые кластеры Лот 1)',
            'lot_2' => 'Контрактация (Малые кластеры Лот 2)' ,
        ];
        $query = $entityManager->getRepository(ContractingTables::class)
            ->createQueryBuilder('a')
            ;

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );


        return $this->render('inspector_contracting/history.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'contracts' => $pagination,
            'dict' => $dict
        ]);
    }
}
