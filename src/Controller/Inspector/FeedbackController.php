<?php

namespace App\Controller\Inspector;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journalist")
 */
class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="app_feedback")
     */
    public function SendFeedback(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
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

        return $this->render('journalist/templates/feedback.html.twig', [
            'controller_name' => 'FeedbackController',
            'pagination' => $pagination
        ]);

    }
}
