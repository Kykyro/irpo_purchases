<?php

namespace App\Controller\Region;

use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use App\Services\XlsxService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;



/**
 * @Route("/region")
 */
class RegionController extends AbstractController
{
    /**
     * @Route("/purchases/", name="app_purchases")
     */
    public function purchases(): Response
    {
        return $this->render('purchases/base.html.twig', [
            'controller_name' => 'RegionController',
        ]);
    }



    public function purchasesDetail(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $routeName = $request->attributes->get('_route');

        // Настраиваем переменные в зависимости от операции
        if ($routeName == 'app_purchases_edit'){
            $title = 'Редактирование';
            $is_disabled = false;
            $isEdit = true;
        }
        else{
            $title = 'Добавление';
            $is_disabled = false;
            $isEdit = false;
        }


        if($routeName == 'app_purchases_detail' or $routeName == 'app_purchases_edit'){
            $repository = $this->getDoctrine()->getRepository(ProcurementProcedures::class);
            $procurement_procedure = $repository->find($id);

            if(!$procurement_procedure){
                return $this->redirectToRoute("app_main");
            }
        }
        else{
            $procurement_procedure = new ProcurementProcedures();
            $user = $this->getUser();
            $procurement_procedure->setUser($user);
        }


        // генерируем форму
        $form = $this->createForm(purchasesFormType::class, $procurement_procedure);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            if($procurement_procedure->getMethodOfDetermining() === 'Другое')
            {
                $procurement_procedure->setMethodOfDetermining($form['anotherMethodOfDetermining']->getData());
            }
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('purchases_files_directory'),
                        $newFilename
                    );
                    $procurement_procedure->setFileDir($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            $procurement_procedure->setChangeTime(new \DateTime('now'));
            $procurement_procedure->UpdateVersion();

            $entity_manager->persist($procurement_procedure);
            $entity_manager->flush();

            $nextAction = $form->get('saveAndAddNew')->isClicked()
                ? 'app_purchases_add'
                : 'app_main';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('purchases_detail/templates/table_add.html.twig', [
            'controller_name' => 'RegionController',
            'form' => $form->createView(),
            'edit' => $isEdit,
            'title' => $title,
            'method' => $procurement_procedure->getMethodOfDetermining()
        ]);
    }
    /**
     * @Route("/purchases-history/{id}", name="app_purchases_history")
     */
    public function historyPurchases(Request $request, int $id, EntityManagerInterface $em, PaginatorInterface $paginator) : Response
    {

        $query = $em->getRepository(Log::class)
            ->createQueryBuilder('a')
            ->andWhere('a.object_class = :obj')
            ->andWhere('a.foreign_key = :fk')
            ->orderBy('a.version', 'DESC')
            ->setParameter('obj', "ProcurementProcedures")
            ->setParameter('fk', $id)
            ->getQuery()
            ;
        $dictionary = [
            'PurchaseObject' => 'Объект закупки',
            'MethodOfDetermining' => 'способ определение поставщика',
            'PurchaseLink' => 'ссылка на закупку',
            'PurchaseNumber' => 'номер закупки',
            'DateOfConclusion' => 'дата заключения договора',
            'DeliveryTime' => 'время поставки',
            'Comments' => 'комментарий',
            'fileDir' => 'название файла Договор/ предмет договора',
            'initialFederalFunds' => 'начальный ФБ',
            'initialFundsOfSubject' => 'начальный РБ',
            'initialEmployersFunds' => 'начальный РД',
            'initialEducationalOrgFunds' => 'начальный ОО',
            'supplierName' => 'поставщик',
            'supplierINN' => 'ИНН поставщика',
            'supplierKPP' => 'КПП поставщика',
            'finFederalFunds' => 'цена контракта ФБ',
            'finFundsOfSubject' => 'цена контракта РБ',
            'finEmployersFunds' => 'цена контракта РД',
            'finFundsOfEducationalOrg' => 'цена контракта ОО',
            'publicationDate' => 'дата публицации',
            'deadlineDate' => 'дата оканчания подачи заявок',
            'dateOfSummingUp' => '',
            'postponementDate' => 'Дата переноса',
            'postonementComment' => 'Комментарий переноса',
            'isPlanned' => 'На стадии планирования?',
            'isHasPrepayment' => 'Есть авансовый платеж?',
            'prepayment' => 'Авансовый платеж %',
            'conractStatus' => 'Статус договора',
            'factFederalFunds' => 'Фактическое расходование ФБ',
            'factFundsOfSubject' => 'Фактическое расходование РБ',
            'factEmployersFunds' => 'Фактическое расходование РД',
            'factFundsOfEducationalOrg' => 'Фактическое расходование ОО',
            'closingDocument' => 'Закрывающий документ',
            'paymentOrder' => 'Платежное поручение',
            'additionalAgreement' => 'Дополнительное соглащение',
            'hasAdditionalAgreement' => 'Есть дополнительное соглашение?',
            'prepaymentDate' => 'дата авансового платежа',
            '' => '',
        ];
//        $count = $query->getSingleScalarResult();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('purchases_detail/templates/history.html.twig', [
            'controller_name' => 'RegionController',
            'pagination' => $pagination,
            'field_dict' => $dictionary,
        ]);
    }

    /**
     * @Route("/purchases-delete/{id}", name="app_purchases_delete")
     */
    public function deletePurchases(Request $request, int $id) : Response
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $pp = $entity_manager->find(ProcurementProcedures::class, $id);
        $pp->setIsDeleted(true);
        $entity_manager->persist($pp);
        $entity_manager->flush();

        return $this->redirectToRoute('app_main');
    }

    /**
     * @Route("/profile", name="app_profile")
     */
    public function userCabinet(): Response
    {
        $user = $this->getUser();
        $user_info = $user->getUserInfo();
//        dd($user_info);
        return $this->render('user_profile/base.html.twig', [
            'controller_name' => 'DefaultController',
            'user_info' => $user_info
        ]);
    }

    /**
     * @Route("/profile/edit", name="app_profile_edit", methods="GET|POST")
     */
    public function userCabinetEdit(Request $request): Response
    {
        return $this->redirectToRoute('app_profile');
        $entity_manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $user_info = $user->getUserInfo();

        $form = $this->createFormBuilder($user_info)
            ->add("rf_subject", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => RfSubject::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add("organization", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false
                ])
            ->add("educational_organization", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false
                ])
            ->add("cluster", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false
                ])
            ->add("declared_industry", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false
                ])
            ->add('year', ChoiceType::class, [
                'choices'  => [
                    '2021' => 2021,
                    '2022' => 2022,
                    '2023' => 2023,
                    '2024' => 2024,
                    '2025' => 2025,
                    '2026' => 2026,
                    '2027' => 2027,
                    '2028' => 2028,
                    '2029' => 2029,
                    '2030' => 2030,
                ],
                'required'   => true,
                'attr' => ['class' => 'form-control'],
                ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entity_manager->persist($user_info);
            $entity_manager->flush();
            return $this->redirectToRoute("app_profile");
        }

        return $this->render('user_profile/edit/base.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/main", name="app_main")
     */
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {

        $user = $this->getUser();
        $user_id = $user->getId();

        $form = $this->createFormBuilder([])
            ->add('search', TextType::class, [
                'attr' => [
                    'class' => 'form-control mr-3 col-md-9'
                ],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mr-3 col-md-1'
                ],
                'label' => 'Поиск'
            ])
            ->add('cancel', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger col-md-1'
                ],
                'label' => 'Сбросить'
            ])
            ->setMethod('GET')
            ->getForm();

        $purchasesWithNote = $em->getRepository(PurchaseNote::class)
            ->createQueryBuilder('n')
            ->leftJoin('n.purchase', 'p')
            ->andWhere('p.user = :user')
            ->andWhere('n.isRead = :isRead')
            ->setParameter('user', $user)
            ->setParameter('isRead', false)
            ->getQuery()
            ->getResult();
        $query = $em->getRepository(ProcurementProcedures::class)
            ->createQueryBuilder('a')
            ->andWhere('a.user = :user_id')
            ->andWhere('a.isDeleted = :is_deleted')
            ->orderBy('a.id', 'DESC')
            ->setParameter('user_id', "$user_id")
            ->setParameter('is_deleted', false)
            ;
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            if($form->get('submit')->isClicked())
            {
                $search = $form->getData();
                $search = $search['search'];
                $query = $query
                    ->andWhere('a.PurchaseObject LIKE :PurchaseObject')
                    ->setParameter('PurchaseObject', "%$search%")
                ;
            }

        }

        $query = $query->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('region/base.html.twig', [
            'controller_name' => 'RegionController',
            'procurement_procedures' => $pagination,
            'purchases_with_note' => $purchasesWithNote,
            'form' => $form->createView(),
        ]);
    }
//@Route("/purchases-edit/{id}", name="app_purchases_edit", methods="GET|POST")
// @Route("/purchases-add", name="app_purchases_add", methods="GET|POST")

    public function purchasesDetailNew(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $routeName = $request->attributes->get('_route');

        // Настраиваем переменные в зависимости от операции
        if ($routeName == 'app_purchases_edit'){
            $title = 'Редактирование';
            $is_disabled = false;
            $isEdit = true;
        }
        else{
            $title = 'Добавление';
            $is_disabled = false;
            $isEdit = false;
        }


        if($routeName == 'app_purchases_detail' or $routeName == 'app_purchases_edit'){
            $repository = $this->getDoctrine()->getRepository(ProcurementProcedures::class);
            $procurement_procedure = $repository->find($id);

            if(!$procurement_procedure){
                return $this->redirectToRoute("app_main");
            }
        }
        else{
            $procurement_procedure = new ProcurementProcedures();
            $user = $this->getUser();
            $procurement_procedure->setUser($user);
        }


        // генерируем форму
        $form = $this->createForm(purchasesFormType::class, $procurement_procedure);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            if($procurement_procedure->getMethodOfDetermining() === 'Другое')
            {
                $procurement_procedure->setMethodOfDetermining($form['anotherMethodOfDetermining']->getData());
            }
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('purchases_files_directory'),
                        $newFilename
                    );
                    $procurement_procedure->setFileDir($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            $procurement_procedure->setChangeTime(new \DateTime('now'));
            $procurement_procedure->UpdateVersion();

            $entity_manager->persist($procurement_procedure);
            $entity_manager->flush();

            $nextAction = $form->get('saveAndAddNew')->isClicked()
                ? 'app_purchases_add'
                : 'app_main';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('region/templates/new_form_purchases.html.twig', [
            'controller_name' => 'RegionController',
            'form' => $form->createView(),
            'edit' => $isEdit,
            'title' => $title,
            'method' => $procurement_procedure->getMethodOfDetermining()
        ]);
    }
    /**
     * @Route("/get/xlsx/", name="download_region_xlsx")
     */
    public function download(XlsxService $xlsxService): Response
    {
        $user = $this->getUser();
        return $xlsxService->generatePurchasesProcedureTable($user->getId());
    }

}
