<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleViewController extends AbstractController
{
    /**
     * @Route("/article-view", name="app_article_view")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(Article::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article_view/index.html.twig', [
            'controller_name' => 'ArticleViewController',
            'pagination' => $pagination
        ]);
    }
}