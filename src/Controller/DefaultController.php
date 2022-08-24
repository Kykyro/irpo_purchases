<?php

namespace App\Controller;

//use Doctrine\DBAL\Types\TextType;
use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
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

/**
 * @Route("/region")
 */
class DefaultController extends AbstractController
{

    /**
     * @Route("/purchases/", name="app_purchases")
     */
    public function purchases(): Response
    {
        return $this->render('purchases/base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/purchases/{id}", name="app_purchases_detail", methods="GET")
     */
    public function purchasesView(Request $request, int $id) : Response
    {
        $title = 'Просмотр';
        $purchase = $this->getDoctrine()
            ->getRepository(ProcurementProcedures::class)
            ->find($id);

        return $this->render('purchases_detail/templates/table_view.html.twig', [
            'controller_name' => 'DefaultController',
            'title' => $title,
            'purchase' => $purchase->getAsRow(),
            'file' => $purchase->getFileDir(),
            'versionInfo' => $purchase->getVersionInfoAndDate(),

        ]);
    }

    /**
     *
     * @Route("/purchases-edit/{id}", name="app_purchases_edit", methods="GET|POST")
     * @Route("/purchases-add", name="app_purchases_add", methods="GET|POST")
     */
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
            $id = $procurement_procedure->getId();
        }

//        $arr = $this->json($procurement_procedure)->getContent();
//        $arr = json_decode($arr, true);
//        var_dump($arr);
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
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
            'edit' => $isEdit,
            'title' => $title,
            'method' => $procurement_procedure->getMethodOfDetermining()
        ]);
    }
    /**
     * @Route("/purchases-history/{id}", name="app_purchases_history")
     */
    public function historyPurchases(Request $request, int $id) : Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Log::class);
        $history = $repository->findBy([
           'object_class' => 'ProcurementProcedures',
           'foreign_key' => $id,
        ], [
            'version' => 'DESC'
        ]);


        return $this->render('purchases_detail/templates/history.html.twig', [
            'controller_name' => 'DefaultController',
            'history' => $history
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
    public function index(): Response
    {

        $user = $this->getUser();
        $user_id = $user->getId();
        $repository = $this->getDoctrine()->getRepository(ProcurementProcedures::class);
        $procurement_procedures = $repository->findBy(
            [
                'user' => $user_id,
                'isDeleted' => false
            ]
        );

        return $this->render('index/base.html.twig', [
            'controller_name' => 'DefaultController',
            'procurement_procedures' => $procurement_procedures
        ]);
    }


}
