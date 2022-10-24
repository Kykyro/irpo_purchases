<?php

namespace App\Controller\Landing;

use App\Entity\Article;
use App\Entity\DesignProjectExample;
use App\Entity\Employees;
use App\Entity\Feedback;
use App\Entity\PartnersLogo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StartLandingController extends AbstractController
{
    /**
     * @Route("/", name="app_start_landing")
     */
    public function index(Request $request): Response
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

        $employees = $entity_manager->getRepository(Employees::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();

        $designProjectExample = $entity_manager->getRepository(DesignProjectExample::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();

        $partners = $entity_manager->getRepository(PartnersLogo::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();

        $feedackForm->handleRequest($request);
        if($feedackForm->isSubmitted() and $feedackForm->isValid()){
            $formData = $feedackForm->getData();
            $entity_manager->persist($feedack);
            $entity_manager->flush();
        }

        return $this->render('start_landing/index.html.twig', [
            'controller_name' => 'StartLandingController',
            'news' => $news,
            'feedbackForm' => $feedackForm->createView(),
            'employees' => $employees,
            'designProjectExample' => $designProjectExample,
            'partners' => $partners
        ]);
    }
}
