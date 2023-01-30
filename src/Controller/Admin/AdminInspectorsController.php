<?php

namespace App\Controller\Admin;

use App\Entity\FavoritesClusters;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Form\UserEditFormType;
use App\Form\RegistrationFormType;
use App\Form\UserPasswordEditFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 *
 * @Route("/admin")
 *
 */
class AdminInspectorsController extends AbstractController
{
    /**
     * @Route("/inspectors", name="app_admin_inspectors")
     */
    public function getInspectors(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {

        $data = [];
        $form = $this->createFormBuilder($data)
            ->add("search", TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'label' => 'Имя пользователя'
            ])

            ->add("submit", SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-3 mb-3'],
                'label' => 'Поиск'
            ])
            ->setMethod('GET')
            ->getForm();



        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', "%INSPECTOR%")
            ->orderBy('u.id', 'DESC')
            ->getQuery();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $search = $form_data['search'];
            $query = $em->getRepository(User::class)
                ->createQueryBuilder('u')
                ->andWhere('u.uuid LIKE :uuid')
                ->andWhere('u.roles LIKE :roles')
                ->setParameter('roles', "%REGION%")
                ->setParameter('uuid', "%$search%");

            $query = $query->orderBy('u.id', 'DESC')->getQuery();
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );



        return $this->render('admin/templates/inspectors.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/inspector-favourite/{id}", name="app_admin_favourite_clusters")
     */
    public function favouriteClusters(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator, int $id): Response
    {

        $query = $em->getRepository(FavoritesClusters::class)
            ->createQueryBuilder('u')
            ->andWhere('u.inspectorId = :inspector')
            ->setParameter('inspector', $id)
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult();
        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult();
//dd($query);
//        $data = [];
        $form = $this->createFormBuilder($query)
//            ->add('clustersId', EntityType::class, [
//                'attr' => ['class' => 'form-control'],
//                'required'   => false,
//                'class' => User::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('sub')
//                        ->orderBy('sub.uuid', 'ASC');
//                },
//                'choice_label' => 'uuid',
//                'multiple' => true,
//                'expanded' => true,
//            ])
            ->add('tags', CollectionType::class,[
                'entry_type' => User::class,
                // these options are passed to each "email" type
                'entry_options' => [
                    'attr' => ['class' => 'form-control'],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ]
            ])
            ->getForm();




        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }





        return $this->render('admin/templates/inspectorFavouriteClusters.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }
  
}
