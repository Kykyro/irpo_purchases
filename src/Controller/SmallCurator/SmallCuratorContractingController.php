<?php

namespace App\Controller\SmallCurator;

use App\Entity\FavoritesClusters;
use App\Entity\InfrastructureSheetRegionFile;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\InspectorPurchasesFindFormType;
use App\Services\ContractingXlsxService;
use App\Services\ReadinessMapXlsxService;
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
    public function index(Request $request, ContractingXlsxService $contractingXlsxService, ReadinessMapXlsxService $readinessMapXlsxService): Response
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

                ],
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Контрактация' => 1,
                    'Карта готовности(ремонт)' => 2,

                ],
            ])
            ->add('lot', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Лот 1' => 1,
                    'Лот 2' => 2,

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
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($data['lot'] == 1)
            {
                $_role = "lot_1";
            }
            else{
                $_role = "lot_2";
            }
            if ($data['type'] == 1) {
                $data['date'] = ($data['date']) ? $data['date'] :  new \DateTime('now');
                return $contractingXlsxService->downloadTable($data['year'], $data['date'], $_role);
            }
            if ($data['type'] == 2) {
                return $readinessMapXlsxService->downloadTable($data['year'], $_role);
            }


        }
        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'form' => $form->createView(),
            'role' => $role
        ]);
    }

}