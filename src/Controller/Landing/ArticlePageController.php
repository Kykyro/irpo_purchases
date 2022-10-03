<?php

namespace App\Controller\Landing;

use App\Entity\Article;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlePageController extends AbstractController
{
    /**
     * @Route("/articles", name="app_article_page")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $data = [];
        $search = null;
        $searchArticleForm = $this->createFormBuilder($data)
            ->add("searchText", TextType::class)
            ->add("submit", SubmitType::class)
            ->getForm();


        $searchArticleForm->handleRequest($request);
        if($searchArticleForm->isSubmitted() and $searchArticleForm->isValid()){
            $form_data = $searchArticleForm->getData();
            $search = $form_data['searchText'];
            $query = $em->getRepository(Article::class)
                ->createQueryBuilder('a')
                ->andWhere('a.title LIKE :search')
                ->orderBy('a.id', 'DESC')
                ->setParameter('search', "%$search%")
                ->getQuery();
        }
        else{
            $query = $em->getRepository(Article::class)
                ->createQueryBuilder('a')
                ->orderBy('a.id', 'DESC')
                ->getQuery();
        }


        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('article_page/index.html.twig', [
            'controller_name' => 'ArticlePageController',
            'pagination' => $pagination,
            'form' => $searchArticleForm->createView(),
            'searchText' => $search
        ]);
    }
}
