<?php

namespace App\Controller\Admin;

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
class AdminController extends AbstractController
{
    /**
     * @Route("/main", name="app_admin")
     */
    public function adminPanel(): Response
    {

        return $this->render('admin/templates/menu.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/users", name="app_admin_users")
     */
    public function adminPanelUsers(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
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
            ->orderBy('u.id', 'DESC')
            ->getQuery();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $search = $form_data['search'];
            $query = $em->getRepository(User::class)
                ->createQueryBuilder('u')
                ->andWhere('u.uuid LIKE :uuid')
                ->setParameter('uuid', "%$search%");

            $query = $query->orderBy('u.id', 'DESC')->getQuery();
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('admin/templates/users.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users-edit/{id}", name="app_admin_user_edit", methods="GET|POST")
     */
    public function adminPanelUsersEdit(Request $request, int $id, UserPasswordHasherInterface $passwordHasher, FormFactoryInterface $formFactory): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);
        $formUser = $formFactory->createNamed('userForm', UserEditFormType::class, $user);
        $formPassword = $formFactory->createNamed('passwordForm', UserPasswordEditFormType::class, $user);

        $userInfo = $user->getUserInfo();
        $formUserInfo = $this->createFormBuilder($userInfo)
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

        $formUser->handleRequest($request);
        if($formUser->isSubmitted() && $formUser->isValid())
        {
            $data = $formUser->getData();



            $user->setRoles($data->getRoles());
            $entity_manager->persist($user);
            $entity_manager->flush();
        }

        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $plaintextPassword = $formPassword->getData()->getPassword();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $entity_manager->persist($user);
            $entity_manager->flush();
        }

        $formUserInfo->handleRequest($request);
        if($formUserInfo->isSubmitted() && $formUserInfo->isValid())
        {
            $user->setUserInfo($userInfo);
            $entity_manager->persist($user);
            $entity_manager->flush();
        }

        return $this->render('admin/templates/userEdit.html.twig', [

            'controller_name' => 'AdminController',
            'userForm' => $formUser->createView(),
            'passwordForm' => $formPassword->createView(),
            'userInfoForm' => $formUserInfo->createView(),
            'thisUser' => $user
        ]);
    }
    /**
     * @Route("/users-add", name="app_admin_user_add", methods="GET|POST")
     */
    public function adminPanelUsersAdd(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $user = new \App\Entity\User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            return $this->redirectToRoute("app_admin_users");
        }

        return $this->render('admin/templates/userAdd.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user-purchases-show/{id}", name="app_admin_user_purchases_show")
     */
    public function showPurchasesByUser(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);
        $pp = $this->getDoctrine()->getRepository(ProcurementProcedures::class)->findBy(
            [ 'user' => $user]
        );


        return $this->render('admin/templates/purchasesShow.html.twig', [
            'pp' => $pp
        ]);
    }

    /**
     * @Route("/user-purchases-repair/{id}", name="app_admin_purchases_repair")
     */
    public function repairPurchases(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $pp = $entity_manager->find(ProcurementProcedures::class, $id);
        $pp->setIsDeleted(false);
        $entity_manager->persist($pp);
        $entity_manager->flush();

        return $this->redirectToRoute('app_admin_user_purchases_show',
            [
                'id' => $pp->getuser()->getID()
            ]);
    }
    /**
     * @Route("/user-purchases-delite/{id}", name="app_admin_purchases_delete")
     */
    public function deletePurchases(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $pp = $entity_manager->find(ProcurementProcedures::class, $id);
        $pp->setIsDeleted(true);
        $entity_manager->persist($pp);
        $entity_manager->flush();

        return $this->redirectToRoute('app_admin_user_purchases_show',
            [
                'id' => $pp->getuser()->getID()
            ]);
    }
    /**
     * @Route("/user-delete/{id}", name="app_admin_user_delete")
     */
    public function deleteUser(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $user = $entity_manager->getRepository(User::class)->find($id);

        $entity_manager->remove($user);
        $entity_manager->flush();

        return $this->redirectToRoute('app_admin_users');
    }
}
