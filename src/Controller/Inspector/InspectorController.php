<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterDocument;
use App\Entity\Log;
use App\Entity\MonitoringCheckOut;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\clusterDocumentForm;
use App\Form\InspectorPurchasesFindFormType;
use App\Form\inspectorUserEditFormType;
use App\Services\FileService;
use App\Services\XlsxRepairNeededService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorController extends AbstractController
{
    /**
     * @Route("/main", name="app_inspector_main", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(InspectorPurchasesFindFormType::class);




        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('app_inspector_research',
                [
                    'rf_sub' => $data['rf_subject']->getid(),
                    'year' => $data['year'],
                ]);



            $repository = $this->getDoctrine()->getRepository(ProcurementProcedures::class);
//            найти нужных пользователей
            $repositoryUI = $this->getDoctrine()->getRepository(UserInfo::class);
            $users_info = $repositoryUI->findBy(
                [
                    'rf_subject' => $data['rf_subject']
                ]
            );
            $repositoryUser = $this->getDoctrine()->getRepository(User::class);
            $users = $repositoryUser->findBy(
                [
                    'user_info' => $users_info
                ]
            );

            $prodProc = $repository->findByYearAndRegion($data['year'], $data['rf_subject'], $users);


            return $this->render('inspector/index.html.twig', [
                'controller_name' => 'InspectorController',
                'form' => $form->createView(),
                'purchases' => $prodProc
            ]);

        }



        return $this->render('inspector/index.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/organization_research/{rf_sub}/{year}", name="app_inspector_research", methods="GET|POST")
     */
    public function orgResearch(Request $request, int $rf_sub, int $year): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $defaultData = [];

        $RFSub = $entity_manager->find(RfSubject::class, $rf_sub);
        $orgForm = $this->createFormBuilder($defaultData)
            ->add("orgName", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'class' => UserInfo::class,
                'query_builder' => function (EntityRepository $er) use($rf_sub)
                {
                    return $er->createQueryBuilder('sub')
                        ->andWhere("sub.rf_subject = :rf_sub")
                        ->setParameter('rf_sub', $rf_sub)
                        ->andWhere('sub.organization IS NOT NULL')

                        ->orderBy('sub.organization', 'ASC');
                },
                'choice_label' => 'organization',
            ])
            ->getForm();;

        $orgForm->handleRequest($request);
        if ($orgForm->isSubmitted() && $orgForm->isValid()) {
            $form_data = $orgForm->getData();
            if($form_data['orgName'] === null)
            {
                return $this->render('inspector/orgSearch.html.twig', [
                    'controller_name' => 'InspectorController',
                    'form' => $orgForm->createView(),
                    'RFsubject' => $RFSub,
                    'year' => $year
                ]);
            }
            $repository = $this->getDoctrine()->getRepository(ProcurementProcedures::class);

            $repositoryUI = $this->getDoctrine()->getRepository(UserInfo::class);
            $users_info = $repositoryUI->findBy(
                [
                    'rf_subject' => $RFSub,
                    'educational_organization' => $form_data['orgName']->getEducationalOrganization()
                ]
            );
            $repositoryUser = $this->getDoctrine()->getRepository(User::class);
            $users = $repositoryUser->findBy(
                [
                    'user_info' => $users_info
                ]
            );

            $prodProc = $repository->findByYearAndRegion($year, $RFSub, $users);
            $prodProc = $prodProc[0];

            return $this->render('inspector/orgSearch.html.twig', [
                'controller_name' => 'InspectorController',
                'form' => $orgForm->createView(),
                'RFsubject' => $RFSub,
                'year' => $year,
                'prodProc' => $prodProc
            ]);
        }

        return $this->render('inspector/orgSearch.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $orgForm->createView(),
            'RFsubject' => $RFSub,
            'year' => $year
        ]);
    }

    /**
     * @Route("/get/xlsx/{user_id}/{year}", name="download_xlsx")
     */
    public function download(XlsxService $xlsxService, int $user_id, int $year): Response
    {
        return $xlsxService->generatePurchasesProcedure($user_id, $year);
    }

    /**
     * @Route("/cluster-info/{id}", name="app_inspector_show_info_about_cluster")
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
     * @Route("/monitoring-check-out-upload/{id}", name="app_inspector_monitoring_check_out_upload")
     */
    public function monitoringCheckOutUpload(int $id, Request $request, FileService $fileService, EntityManagerInterface $em)
    {
        $submittedToken = $request->request->get('token');
        $user = $em->getRepository(User::class)->find($id);
        $userInfo = $user->getUserInfo();
//        dd( $request->files);
        if ($this->isCsrfTokenValid('check-out', $submittedToken)) {

            if($request->files->get('file')) {
                $checkOut = new MonitoringCheckOut();
                $checkOut->setDate(new \DateTimeImmutable($request->request->get('date')));
                $checkOut->setFile($fileService->UploadFile($request->files->get('file'), 'monitoring_check_out_directory'));
                $checkOut->setUserInfo($userInfo);

                $em->persist($checkOut);
                $em->flush();
            }


        }

        return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $id]);
    }

    /**
     * @Route("/cluster-info-edit/{id}", name="app_inspector_edit_info_about_cluster")
     */
    public function editUserInfo(int $id, Request $request, FileService $fileService){

        $entity_manger = $this->getDoctrine()->getManager();
        $user_info = $entity_manger->getRepository(UserInfo::class)->find($id);

        $form = $this->createForm(inspectorUserEditFormType::class, $user_info);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {

            $photo = $form->get('_photo')->getData();

            if($photo)
                $user_info->setPhoto($fileService->UploadFile($photo, 'cluster_photo_directory'));

            $arr = [];
            $key = 1;
            $orgList = $user_info->getListOfEdicationOrganization();
            foreach (array_keys($orgList) as $i)
            {
                $arr[$key] = $orgList[$i];
                $key++;
            }
            $user_info->setListOfEdicationOrganization($arr);

            $arr = [];
            $key = 1;
            $empList = $user_info->getListOfEmployers();
            foreach (array_keys($empList) as $i)
            {
                $arr[$key] = $empList[$i];
                $key++;
            }
            $user_info->setListOfEmployers($arr);

            $arr = [];
            $key = 1;
            $empList = $user_info->getListOfAnotherOrganization();
            foreach (array_keys($empList) as $i)
            {
                $arr[$key] = $empList[$i];
                $key++;
            }
            $user_info->setListOfAnotherOrganization($arr);

            $entity_manger->persist($user_info);
            $entity_manger->flush();
            $user = $entity_manger->getRepository(User::class)
                ->findBy([
                    'user_info' => $user_info,
                ]);
            return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $user[0]->getId()]);
        }


        return $this->render('inspector/templates/editUserInfo.html.twig', [
            'controller_name' => 'InspectorController',
            'userInfoForm' => $form->createView(),

        ]);

    }

    /**
     * @Route("/cluster-document-edit/{id}", name="app_inspector_edit_cluster_document")
     */
    public function editClusterDocument(int $id, Request $request, FileService $fileService){

        $entity_manger = $this->getDoctrine()->getManager();
        $user_info = $entity_manger->getRepository(UserInfo::class)->find($id);
        $user = $entity_manger->getRepository(User::class)->getUserByUserInfo($user_info);
        $clusterDocument = $user_info->getClusterDocument();
        if(!$clusterDocument)
        {
            $clusterDocument = new ClusterDocument();
            $user_info->setClusterDocument($clusterDocument);
        }

        $formLabels = [
          'InfrastructureSheet' => in_array('ROLE_BAS', $user->getRoles()) ? 'Инфраструктурный лист специализированного класса (кружка)' : 'Инфраструктурный лист',
        ];

        $form = $this->createForm(clusterDocumentForm::class, $clusterDocument, ['labels' => $formLabels]);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $PartnershipAgreement = $form->get('PartnershipAgreement')->getData();
            $FinancialAgreement = $form->get('FinancialAgreement')->getData();
            $InfrastructureSheet = $form->get('InfrastructureSheet')->getData();
            $DesignProject = $form->get('DesignProject')->getData();
            $ActivityProgram = $form->get('ActivityProgram')->getData();
            $notFinancialAgreement = $form->get('notFinancialAgreement')->getData();
            $infrastructureSheetPractice = $form->get('infrastructureSheetPractice')->getData();

            if($PartnershipAgreement)
                $clusterDocument->setPartnershipAgreement($fileService->UploadFile($PartnershipAgreement, 'cluster_documents_directory'));
            if($FinancialAgreement)
                $clusterDocument->setFinancialAgreement($fileService->UploadFile($FinancialAgreement, 'cluster_documents_directory'));
            if($InfrastructureSheet)
                $clusterDocument->setInfrastructureSheet($fileService->UploadFile($InfrastructureSheet, 'cluster_documents_directory'));
            if($DesignProject)
                $clusterDocument->setDesignProject($fileService->UploadFile($DesignProject, 'cluster_documents_directory'));
            if($ActivityProgram)
                $clusterDocument->setActivityProgram($fileService->UploadFile($ActivityProgram, 'cluster_documents_directory'));
            if($notFinancialAgreement)
                $clusterDocument->setNotFinancialAgreement($fileService->UploadFile($notFinancialAgreement, 'cluster_documents_directory'));
            if($infrastructureSheetPractice)
                $clusterDocument->setInfrastructureSheetPractice($fileService->UploadFile($infrastructureSheetPractice, 'cluster_documents_directory'));




            $entity_manger->persist($clusterDocument);
            $entity_manger->persist($user_info);
            $entity_manger->flush();

            if(in_array('ROLE_BAS', $user->getRoles()))
            {
                return $this->redirectToRoute('app_bas_curator_info', ['id' => $user->getId()]);
            }
            return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $user->getId()]);

        }


        return $this->render('inspector/templates/editClusterDocument.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView(),
            'errors' => $form->getErrors()

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
     * @Route("/repair-download/{id}", name="app_inspector_download_xlsx")
     */
    public function downloadXlsx(Request $request, XlsxRepairNeededService $service, int $id, EntityManagerInterface $em)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');
        $user = $em->getRepository(User::class)->find($id);

        if ($this->isCsrfTokenValid('download_xlsx', $submittedToken)) {
            return $service->generate($year, $user);
        }

        throw new Exception('Ошибка');
    }

    /**
     * @Route("/repair-download-all", name="app_inspector_download_xlsx_all")
     */
    public function downloadXlsxAll(Request $request, XlsxRepairNeededService $service, EntityManagerInterface $em)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');


        if ($this->isCsrfTokenValid('download_xlsx', $submittedToken)) {
            return $service->generateAllByYear($year);
        }

        throw new Exception('Ошибка');
    }


//    /**
//     * @Route("/a", name="a")
//     */
//    public function purchases(Request $request): Response
//    {
//
//
//    }
}
