<?php

namespace App\Controller;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
        $entity_manager = $this->getDoctrine()->getManager();

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
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
            ])
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
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
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
}
