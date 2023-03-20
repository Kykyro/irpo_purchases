<?php

namespace App\Controller;

use App\Form\UserPasswordEditFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserPasswordChangeController
 * @package App\Controller
 *  @Route("/user")
 */
class UserPasswordChangeController extends AbstractController
{
    /**
     * @Route("/password-change", name="app_user_password_change")
     */
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $formPassword = $this->createForm(UserPasswordEditFormType::class, $user);

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

            return $this->render('user_password_change/index.html.twig', [
                'controller_name' => 'UserPasswordChangeController',
                'form' => $formPassword->createView(),
                'success' => true
            ]);
        }


        return $this->render('user_password_change/index.html.twig', [
            'controller_name' => 'UserPasswordChangeController',
            'form' => $formPassword->createView(),
            'success' => false,
        ]);
    }
}
