<?php

namespace App\Controller\Inspector;

use App\Entity\ContractingTables;
use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapSaves;
use App\Entity\User;
use App\Services\ContractingXlsxService;
use App\Services\ReadinessMapXlsxService;
use App\Services\XlsxPerformanceIndicatorService;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function index(Request $request, ContractingXlsxService $contractingXlsxService,
                          ReadinessMapXlsxService $readinessMapXlsxService, XlsxPerformanceIndicatorService $indicatorService): Response
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
                    '2024 год' => 2024,
                    '2025 год' => 2025,

                ],
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    'Контрактация' => 1,
                    'Карта готовности(все)' => 2,
                    'Карта готовности(ремонт)' => 3,
                    'Карта готовности(оборудование)' => 4,
                    'Показатели результативности' => 5,
                    'Ремработы и оборудование (новый)' => 6,

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
            ->add('start', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    "min"  => 0,
                    'max' => 200,
                    'step' => 10
                ],
                'label' => 'Старт',
                'empty_data' => 0,
                'required'   => false,
                'data' => 0,

            ])
            ->add('step', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    "min"  => 0,
                    'max' => 200,
                    'step' => 10
                ],
                'label' => 'Шаг',
                'empty_data' => 0,
                'required'   => false,
                'data' => 0,

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
            if($data['type'] == 3)
            {
                return $readinessMapXlsxService->downloadTableRepair($data['year']);
            }
            if($data['type'] == 4)
            {
                return $readinessMapXlsxService->downloadTableEquipment($data['year'], 'cluster', false, $data['start'], $data['step']);
            }
            if($data['type'] == 5){
                return $indicatorService->generateTable($data['year'], "ROLE_REGION");
            }
            if($data['type'] == 6){
                return $readinessMapXlsxService->downloadTableNew($data['year'], 'cluster', false, $data['start'], $data['step']);
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
            'dict' => $dict,
            'download_path' => 'contracting_tables_directory',
        ]);
    }
    /**
     * @Route("/rm-history", name="app_inspector_rm_history")
     */
    public function history_rm(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $dict = [
            'cluster'=> 'Карты готовности (Производственные кластеры)' ,
            'lot_1' => 'Карты готовности (Малые кластеры Лот 1)',
            'lot_2' => 'Карты готовности (Малые кластеры Лот 2)' ,
        ];
        $query = $entityManager->getRepository(ReadinessMapSaves::class)
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
            'dict' => $dict,
            'download_path' => 'readiness_map_saves_directory',
        ]);
    }
}
