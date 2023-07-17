<?php

namespace App\Controller\Roiv;

use App\Entity\Building;
use App\Entity\ProcurementProcedures;
use App\Entity\ProfEduOrg;
use App\Entity\User;
use App\Services\budgetSumService;
use App\Services\XlsxService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/roiv")
 */
class RoivController extends AbstractController
{
    /**
     * @Route("/list", name="app_roiv")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $orgs = $em->getRepository(ProfEduOrg::class)
            ->findAllByRegion($user->getUserInfo()->getRfSubject()->getId());




        return $this->render('roiv/index.html.twig', [
            'controller_name' => 'ROIVListController',
            'orgs' => $orgs,
        ]);
    }
    /**
     * @Route("/add_org", name="app_roiv_add_org")
     * @Route("/edit_org/{id}", name="app_roiv_edit_org")
     */
    public function addOrg(EntityManagerInterface $em, int $id=null, Request $request): Response
    {
        if($id)
        {
            $org = $em->getRepository(ProfEduOrg::class)->find($id);
        }
        else
        {
            $org = new ProfEduOrg();
            $org->setRegion($this->getUser()->getUserInfo()->getRfSubject());
        }

        $form = $this->createFormBuilder($org)
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Полное наименование профессиональной образовательной организации'
            ])
            ->add('shortName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Сокращенное наименование профессиональной образовательной организации'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and  $form->isValid())
        {

            $em->persist($org);
            $em->flush();
            return $this->redirectToRoute('app_roiv');
        }




        return $this->render('roiv/addOrg.html.twig', [
            'controller_name' => 'ROIVListController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/cluster-info/{id}", name="app_roiv_show_info_about_cluster")
     */
    public function showInfoAboutCluster(int $id){
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);
        $user_info = $user->getUserInfo();

        return $this->render('inspector/templates/infoAboutCluster.html.twig', [
            'controller_name' => 'InspectorController',
            'user_info' => $user_info,
            'user' => $user

        ]);
    }
    /**
     * @Route("/show-purchases/{id}", name="app_roiv_show_purchases", methods="GET|POST")
     */
    public function showPurchases(Request $request, int $id, budgetSumService $budgetSumService, XlsxService $xlsxService, SerializerInterface $serializer): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $prodProc = $entity_manager->getRepository(ProcurementProcedures::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->andWhere('u.id = :id')
            ->andWhere('a.isDeleted = :isDeleted')
            ->setParameter('id', "$id")
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
        $today = new \DateTimeImmutable('now');
        $user = $entity_manager->getRepository(User::class)->find($id);

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required'   => true,
                'label' => 'Дата'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn mt-3'
                ],
                'label' => 'скачать'
            ])
            ->getForm();

        $budgetArr = [];


        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
            return $xlsxService->generatePurchasesProcedureTableWithDate($id, $data['date']);
        }


        return $this->render('roiv/showPurchases.html.twig', [
            'controller_name' => 'InspectorController',
            'prodProc' => $prodProc,
            'id' => $id,
            'initial_sum' => $budgetSumService->getInitialBudget($prodProc, $today),
            'fin_sum' => $budgetSumService->getFinBudget($prodProc, $today),
            'form' => $form->createView(),
            'user' => $user,
            'today' => $today,
        ]);
    }

    /**
     * @Route("/get/purchasses-xlsx/{user_id}", name="download_purchases_xlsx_roiv")
     */
    public function download(XlsxService $xlsxService, int $user_id): Response
    {
        return $xlsxService->generatePurchasesProcedureTable($user_id);
    }
//    /**
//     * @Route("/building-list/{id}", name="app_roiv_building_list")
//     */
//    public function buildingList(EntityManagerInterface $em, int $id): Response
//    {
//        $buildings = $em->getRepository(Building::class)
//            ->findAllByRegion($user->getUserInfo()->getRfSubject()->getId());

//        return $this->render('roiv/buildingList.html.twig', [
//            'controller_name' => 'ROIVListController',
//            'orgs' => $orgs,
//        ]);
//    }
}
