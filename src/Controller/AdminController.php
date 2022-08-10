<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function adminPanelUsers(): Response
    {
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();

        return $this->render('admin/templates/users.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
        ]);
    }

    /**
     * @Route("/users-edit/{id}", name="app_admin_user_edit", methods="GET|POST")
     */
    public function adminPanelUsersEdit(Request $request, int $id): Response
    {
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);


        return $this->render('admin/templates/userEdit.html.twig', [
            'controller_name' => 'AdminController',

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
}
