<?php

namespace App\Controller\Inspector;

use App\Entity\FavoritesClusters;
use App\Entity\InfrastructureSheetRegionFile;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\InspectorPurchasesFindFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
class InfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/clusters", name="app_inspector_infrastructure_sheet", methods="GET|POST")
     */
    public function regionUserList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $_cluster = $entity_manager->getRepository(FavoritesClusters::class)->findBy(
            ['inspectorId' => $user]
        );

        $cluster = [];
        foreach ($_cluster as $i){
//            dd($i->getClusterId());
            array_push($cluster, $i->getCluster()->getId());

        }



        $role = 'ROLE_REGION';

        $form_data = [];
        $form = $this->createFormBuilder($form_data)
            ->add("rf_subject", EntityType::class, [
                'attr' => ['class' => 'form-control select2'],
                'required'   => false,
                'class' => RfSubject::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add("year", ChoiceType::class,[
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'choices'  => [
                    '2021' => 2021,
                    '2022' => 2022,
                    '2023' => 2023,
                    '2024' => 2024,

                ],

            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mt-3'
                ],
                'label' => 'Найти'
            ])
            ->setMethod('GET')
            ->getForm();

        $query = $em->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->where('a.roles LIKE :role')
            ->setParameter('role', "%$role%");

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();

            if($form_data['rf_subject'] !== null){
                $region = $form_data['rf_subject'];
                $query = $query

                    ->andWhere('uf.rf_subject = :rf_subject')
                    ->setParameter('rf_subject', $region);
            }
            if($form_data['year'] !== null){
                $year = $form_data['year'];
                $query = $query

                    ->andWhere('uf.year = :year')
                    ->setParameter('year', $year);
            }
        }

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );



        return $this->render('inspector/templates/infrastructure_sheet.html.twig', [
            'controller_name' => 'InspectorController',
            'pagination' => $pagination,
            'form' => $form->createView(),
            'clusters' => $cluster
        ]);
    }

    /**
     * @Route("/infrastructure-sheet-upload/{id}", name="app_inspector_infrastructure_sheet_upload", methods="GET|POST")
     */
    public function upload_IS(Request $request, SluggerInterface $slugger, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $regionISFile = new InfrastructureSheetRegionFile();
        $user = $entity_manager->getRepository(User::class)->find($id);
        $regionISFile->setUser($user);


        $form = $this->createFormBuilder($regionISFile)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'   => false,
            ])
            ->add('file', FileType::class, [
                'attr' => [
                    'class' => 'form-control col-lg-12'
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ],
                'label' => 'Отправить'
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();


            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('infrastructure_sheet_region_directory'),
                        $newFilename
                    );
                    $regionISFile->setFile($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }

            $entity_manager->persist($regionISFile);
            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_infrastructure_sheet');
        }

        return $this->render('inspector/templates/infrastructure_sheet_upload.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/infrastructure-sheet-region-view/{id}", name="app_inspector_infrastructure_sheet_region_view", methods="GET|POST")
     */
    public function view_IS(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);
        $infrastructureSheets = $entity_manager->getRepository(InfrastructureSheetRegionFile::class)->findBy([
            'user' => $user,

        ], [
            'id' => 'DESC'
        ]);

        return $this->render('region/templates/infrastructure_sheet.html.twig', [
            'controller_name' => 'InspectorController',
            'infrastructureSheets' => $infrastructureSheets,
        ]);
    }

}
