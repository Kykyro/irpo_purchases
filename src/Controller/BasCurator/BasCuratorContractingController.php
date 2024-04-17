<?php

namespace App\Controller\BasCurator;

use App\Entity\ContractingTables;
use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapSaves;
use App\Entity\User;
use App\Services\BAS\UAVsEquipmentTableService;
use App\Services\ContractingXlsxService;
use App\Services\excel\ContactAllPurchasesXlsxService;
use App\Services\ReadinessMapXlsxService;
use App\Services\XlsxPerformanceIndicatorService;

use App\Services\zip\ContractingDownloadZipService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
/**
 *
 * @package App\Controller
 * @Route("/bas-curator")
 */
class BasCuratorContractingController extends AbstractController
{
    /**
     * @Route("/contracting-actual", name="app_bas_curator_contracting")
     */
    public function index(Request $request, ContractingXlsxService $contractingXlsxService,
                          ReadinessMapXlsxService $readinessMapXlsxService, XlsxPerformanceIndicatorService $indicatorService,
                          ContractingDownloadZipService $contractingDownloadZipService, ContactAllPurchasesXlsxService $allPurchasesXlsxService,
                          UAVsEquipmentTableService $equipmentTableService  ): Response
    {

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [

                    '2024 год' => 2024,
                    '2025 год' => 2025,
                    '2026 год' => 2026,

                ],
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    'Контрактация' => 1,
                    'Карта готовности(все)' => 2,
                    'Карта готовности(общая)' => 3,
//                    'Карта готовности(частично)' => 4,
                    'Контрактация (архив)' => 8,
                    'Общая таблица закупок' => 9,
                    'Общая (Наименование БПЛА)' => 10,
//                    'Карта готовности(оборудование)' => 4,
//                    'Показатели результативности' => 5,

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

            ])
//            ->add("tags", EntityType::class, [
//                'attr' => ['class' => 'form-control'],
//                'required'   => false,
//                'class' => UserTags::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('t')
//                        ->orderBy('t.id', 'ASC');
//                },
//                'choice_label' => 'tag',
//                'label' => 'Теги'
//            ])
            ->add("tags", HiddenType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if($data['type'] == 1)
            {
                if($data['date'])
                {
                    return $contractingXlsxService->downloadTableBAS($data['year'], $data['date'], 'bas');
                }
                $today = new \DateTime('now');
                return $contractingXlsxService->downloadTableBAS($data['year'], $today, 'bas');
            }

            if($data['type'] == 2)
            {
                return $readinessMapXlsxService->downloadTableBas($data['year']);
            }
            if($data['type'] == 3)
            {
                return $readinessMapXlsxService->downloadTableNew2($data['year'],'bas');
            }
            if($data['type'] == 4)
            {
                return $readinessMapXlsxService->downloadTableBas($data['year'], false, $data['start'], $data['step']);
            }
            if($data['type'] == 8)
            {
                return $contractingDownloadZipService->download($data['year'], 'ROLE_BAS' );
            }
            if($data['type'] == 9)
            {
                return $allPurchasesXlsxService->tableGeneratorWithFactFundsBas($data['year'], 'ROLE_BAS');
            }
            if($data['type'] == 10)
            {
                return $equipmentTableService->downloadTableAll($data['year'], 'ROLE_BAS');
            }
//            if($data['type'] == 5){
//                return $indicatorService->generateTable($data['year'], "ROLE_REGION");
//            }


        }

        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'form' => $form->createView(),
            'role' => 'bas'
        ]);
    }
//    /**
//     * @Route("/contracting-history", name="app_inspector_contracting_history")
//     */
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
