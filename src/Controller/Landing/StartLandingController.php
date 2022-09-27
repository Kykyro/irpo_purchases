<?php

namespace App\Controller\Landing;

use App\Entity\Article;
use App\Entity\Feedback;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StartLandingController extends AbstractController
{
    /**
     * @Route("/", name="app_start_landing")
     */
    public function index(): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $news = $entity_manager->getRepository(Article::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $feedack = new Feedback();
        $feedackForm = $this->createFormBuilder($feedack)
            ->add("FIO", TextType::class)
            ->add("email", TextType::class)
            ->add("topic", TextType::class)
            ->add("message", TextareaType::class)
            ->add("submit", SubmitType::class)
            ->getForm();

        if($feedackForm->isSubmitted() and $feedackForm->isValid()){

            $entity_manager->persist($feedack);
            $entity_manager->flush();
        }

        return $this->render('start_landing/index.html.twig', [
            'controller_name' => 'StartLandingController',
            'news' => $news,
            'feedbackForm' => $feedackForm->createView()
        ]);
    }
}
