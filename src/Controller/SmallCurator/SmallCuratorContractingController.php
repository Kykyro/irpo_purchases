<?php

namespace App\Controller\SmallCurator;

use App\Entity\FavoritesClusters;
use App\Entity\InfrastructureSheetRegionFile;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Entity\UserTags;
use App\Form\InspectorPurchasesFindFormType;
use App\Services\cofinancing\CofinancingTableService;
use App\Services\ContractingXlsxService;
use App\Services\ReadinessMapXlsxService;
use App\Services\XlsxPerformanceIndicatorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class SmallCuratorContractingController extends AbstractController
{
    /**
     * @Route("/small-cluster/contracting-actual", name="app_inspector_sc_contracting")
     */
    public function index(Request $request, ContractingXlsxService $contractingXlsxService, ReadinessMapXlsxService $readinessMapXlsxService, XlsxPerformanceIndicatorService $indicatorService,
                          CofinancingTableService $cofinancingTableService): Response
    {
        $role = 'small';
        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
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
                'choices' => [
                    'Контрактация' => 1,
                    'Карта готовности(все)' => 2,
//                    'Карта готовности(ремонт)' => 3,
                    'Показатели результативности' => 5,
//                    'Карта готовности(оборудование)' => 4,
                    'Ремработы и оборудование (новый)' => 6,
//                    'Ремработы и оборудование (новый, частично)' => 7,
                    'Софинансирование' => 11,

                ],
            ])
            ->add('lot', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Лот 1' => "lot_1",
                    'Лот 2' => "lot_2",

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
                'required' => false,
                'label' => 'Дата проверки'
            ])

            ->add("tags", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => UserTags::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.id', 'ASC');
                },
                'choice_label' => 'tag',
                'label' => 'Теги'
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tags = key_exists('tags', $data) ? $data['tags'] : null;
            $_role = $data['lot'];

            if ($data['type'] == 1) {
                $data['date'] = ($data['date']) ? $data['date'] :  new \DateTime('now');
                return $contractingXlsxService->downloadTable($data['year'], $data['date'], $_role, null, $tags);
            }
            if ($data['type'] == 2) {
                return $readinessMapXlsxService->downloadTable($data['year'], $_role, false, $tags);
            }
            if($data['type'] == 3)
            {
                return $readinessMapXlsxService->downloadTableRepair($data['year'], $_role);
            }
            if($data['type'] == 4)
            {
                return $readinessMapXlsxService->downloadTableEquipment($data['year'], $_role, false, $data['start'], $data['step']);
            }
            if($data['type'] == 5){
                return $indicatorService->generateTable($data['year'], $_role);
            }
            if($data['type'] == 6){
                return $readinessMapXlsxService->downloadTableNew($data['year'], $_role, false, 0, 200, $tags);
            }
            if($data['type'] == 7){
                return $readinessMapXlsxService->downloadTableNew($data['year'], $_role, false, $data['start'], $data['step'], $tags);
            }
            if($data['type'] == 11){
                return $cofinancingTableService->downloadTable($data['year'], 'ROLE_REGION', $tags);
            }


        }
        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'form' => $form->createView(),
            'role' => $role
        ]);
    }

}
