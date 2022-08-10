<?php

namespace App\Controller;

//use Doctrine\DBAL\Types\TextType;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/system")
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
     * @Route("/purchases/{id}", name="app_purchases_detail", methods="GET")
     * @Route("/purchases-edit/{id}", name="app_purchases_edit", methods="GET|POST")
     * @Route("/purchases-add", name="app_purchases_add", methods="GET|POST")
     */
    public function purchasesDetail(Request $request, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $routeName = $request->attributes->get('_route');

        // Настраиваем переменные в зависимости от операции
        if($routeName == 'app_purchases_detail'){
            $title = 'Просмотр';
            $is_disabled = true;
            $isSave = false;
        }
        else if($routeName == 'app_purchases_edit'){
            $title = 'Редактирование';
            $is_disabled = false;
            $isSave = true;
        }
        else{
            $title = 'Добавление';
            $is_disabled = false;
            $isSave = true;
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

        // генерируем форму
        $form = $this->createFormBuilder($procurement_procedure)
            ->add("PurchaseObject", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'disabled' => $is_disabled
                ])
            ->add("MethodOfDetermining", ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'required'   => true,
                    'disabled' => $is_disabled,
                    'choices'  => [
                        'Единственный поставщик' => 'Единственный поставщик',
                        'Аукцион в электронной форме' => 'Аукцион в электронной форме',
                        'Открытый конкурс' => 'Открытый конкурс',
                        'Запрос котировок' => 'Запрос котировок',
                    ],
                ])
            ->add("PurchaseLink", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("PurchaseNumber", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("DateOfConclusion", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

                ])
            ->add("DeliveryTime", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

                ])
            ->add("Comments", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialFederalFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                        ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialFundsOfSubject", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                        ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialEmployersFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                        ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
           ->add("initialEducationalOrgFunds", TextType::class,
                    [
                        'attr' => [
                            'class' => 'form-control initial',
                            'step' => '.001',
                            'min' => '0',
                            'max' => '99999999999'
                            ],
                        'required'   => false,
                        'disabled' => $is_disabled
                    ])
            ->add("supplierName", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("supplierINN", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("supplierKPP", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finFederalFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finFundsOfSubject", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finEmployersFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finFundsOfEducationalOrg", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'step' => '.001',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("publicationDate", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("deadlineDate", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("dateOfSummingUp", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("postponementDate", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("postonementComment", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entity_manager->persist($procurement_procedure);
            $entity_manager->flush();

            if($routeName == 'app_purchases_add')
                return $this->redirectToRoute("app_main");

            return $this->redirectToRoute("app_purchases_detail", ['id' => $id]);
        }

        return $this->render('purchases_detail/base.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
            'save' => $isSave,
            'title' => $title
        ]);
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
            ['user' => $user_id]
        );

        return $this->render('index/base.html.twig', [
            'controller_name' => 'DefaultController',
            'procurement_procedures' => $procurement_procedures
        ]);
    }


}
