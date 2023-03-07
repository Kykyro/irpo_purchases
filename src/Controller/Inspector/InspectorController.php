<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterDocument;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\clusterDocumentForm;
use App\Form\InspectorPurchasesFindFormType;
use App\Form\inspectorUserEditFormType;
use App\Services\FileService;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
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
            'user_info' => $user_info

        ]);
    }

    /**
     * @Route("/cluster-info-edit/{id}", name="app_inspector_edit_info_about_cluster")
     */
    public function editUserInfo(int $id, Request $request){

        $entity_manger = $this->getDoctrine()->getManager();
        $user_info = $entity_manger->getRepository(UserInfo::class)->find($id);

        $form = $this->createForm(inspectorUserEditFormType::class, $user_info);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {


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

        $clusterDocument = $user_info->getClusterDocument();
        if(!$clusterDocument)
        {
            $clusterDocument = new ClusterDocument();
            $user_info->setClusterDocument($clusterDocument);
        }



        $form = $this->createForm(clusterDocumentForm::class, $clusterDocument);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $PartnershipAgreement = $form->get('PartnershipAgreement')->getData();
            $FinancialAgreement = $form->get('FinancialAgreement')->getData();
            $InfrastructureSheet = $form->get('InfrastructureSheet')->getData();
            $DesignProject = $form->get('DesignProject')->getData();
            $ActivityProgram = $form->get('ActivityProgram')->getData();

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




            $entity_manger->persist($clusterDocument);
            $entity_manger->persist($user_info);
            $entity_manger->flush();
            $user = $entity_manger->getRepository(User::class)
                ->findBy([
                    'user_info' => $user_info,
                ]);
            return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $user[0]->getId()]);
        }


        return $this->render('inspector/templates/editClusterDocument.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView(),

        ]);

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
