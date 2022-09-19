<?php

namespace App\Controller\Landing;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


        return $this->render('start_landing/index.html.twig', [
            'controller_name' => 'StartLandingController',
            'news' => $news
        ]);
    }
}
