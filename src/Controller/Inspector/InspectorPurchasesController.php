<?php

namespace App\Controller\Inspector;

use App\Entity\Log;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\InspectorPurchasesFindFormType;
use App\Services\budgetSumService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;

/**
 *
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorPurchasesController extends AbstractController
{
    /**
     * @Route("/purchases", name="app_inspector_purchases", methods="GET|POST")
     */
    public function index(Request $request): Response
    {


        $form = $this->createForm(InspectorPurchasesFindFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('app_inspector_finded_cluster',
                [
                    'region' => $data['rf_subject']->getid(),
                    'year' => $data['year'],
                ]);
        }

        return $this->render('inspector/templates/findCluster.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/find-clusters/{year}/{region}", name="app_inspector_finded_cluster", methods="GET|POST")
     */
    public function selectCluster(Request $request, int $year, int $region, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $role = 'ROLE_REGION';

        $query = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'rfs')
            ->andWhere('a.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->andWhere('rfs.id = :region')
            ->setParameter('role', "%$role%")
            ->setParameter('year', "$year")
            ->setParameter('region', "$region");

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('inspector/templates/selectCluster.html.twig', [
            'controller_name' => 'InspectorController',
            'pagination' => $pagination,

        ]);
    }
    /**
     * @Route("/show-purchases/{id}", name="app_inspector_show_purchases", methods="GET|POST")
     */
    public function showPurchases(Request $request, int $id, budgetSumService $budgetSumService, XlsxService $xlsxService): Response
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

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
            return $xlsxService->generatePurchasesProcedureTableWithDate($id, $data['date']);
        }

        return $this->render('inspector/templates/showPurchases.html.twig', [
            'controller_name' => 'InspectorController',
            'prodProc' => $prodProc,
            'id' => $id,
            'initial_sum' => $budgetSumService->getInitialBudget($prodProc, $today),
            'fin_sum' => $budgetSumService->getFinBudget($prodProc, $today),
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/view-purchases/{id}", name="app_inspector_view_purchase", methods="GET|POST")
     */
    public function viewFullPurchases(int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $title = 'Просмотр';
        $purchase = $entity_manager
            ->getRepository(ProcurementProcedures::class)
            ->find($id);
        // получаем файлы
        $file_dir = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :file')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('file', "%fileDir%")
            ->getQuery()
            ->getResult();
        $paymentOrder = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :payment')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('payment', "%paymentOrder%")
            ->getQuery()
            ->getResult();
        $closingDocument = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :closing')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('closing', "%closingDocument%")
            ->getQuery()
            ->getResult();
        $additionalAgreement = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :add')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('add', "%additionalAgreement%")
            ->getQuery()
            ->getResult();
//dd($file_dir);

        return $this->render('inspector/templates/viewPu.html.twig', [
            'controller_name' => 'InspectorPurchasesController',
            'title' => $title,
            'purchase' => $purchase,
            'file' => $file_dir,
            'paymentOrder' => $paymentOrder,
            'closingDocument' => $closingDocument,
            'additionalAgreement' => $additionalAgreement,
            'versionInfo' => $purchase->getVersionInfoAndDate(),

        ]);
    }

    /**
     * @Route("/get/purchasses-xlsx/{user_id}", name="download_purchases_xlsx")
     */
    public function download(XlsxService $xlsxService, int $user_id): Response
    {
        return $xlsxService->generatePurchasesProcedureTable($user_id);
    }

    /**
     * @Route("/get/purchasses-xlsx-all-by-year/{year}", name="download_purchases_all_by_year_xlsx")
     */
    public function downloadAllByYear(XlsxService $xlsxService, int $year): Response
    {
        return $xlsxService->generateAllPurchasesProcedureTable($year);
    }
//    /**
//     * @Route("/get/ready-map-xlsx/{year}", name="download_purchases_xlsx")
//     */
    public function downloadReadyMaps(XlsxService $xlsxService, int $year): Response
    {
//        return $xlsxService->generateAllPurchasesProcedureTable($year);
    }
    /**
     * @Route("/add-note/{id}", name="app_inspector_add_note_to_purchase")
     */
    public function addNoteToPurchase(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $purchase = $entity_manager
            ->getRepository(ProcurementProcedures::class)
            ->find($id);
        $note = new PurchaseNote();

        $form = $this->createFormBuilder($note)
            ->add('note', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ],
                'label' => 'Отправить'
            ])
            ->getForm();


        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            $note->setCurator($this->getUser());
            $note->setIsRead(false);
            $note->setPurchase($purchase);
            $note->setCreadtedAt(new \DateTimeImmutable(('now')));

            $entity_manager->persist($note);
            $entity_manager->flush();
            return $this->redirectToRoute('app_inspector_view_purchase', ['id' => $id]);
        }




        return $this->render('inspector/templates/addNote.html.twig', [
            'controller_name' => 'InspectorPurchasesController',
            'form' => $form->createView(),


        ]);

    }
}
