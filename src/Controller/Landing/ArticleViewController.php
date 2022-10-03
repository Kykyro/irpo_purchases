<?php

namespace App\Controller\Landing;

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
     * @Route("/article-view/{id}", name="app_article_view")
     */
    public function index(int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $article = $entity_manager->getRepository(Article::class)->find($id);

        return $this->render('article_view/index.html.twig', [
            'controller_name' => 'ArticleViewController',
            'article' => $article

        ]);
    }
}
