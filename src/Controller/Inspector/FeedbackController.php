<?php

namespace App\Controller\Inspector;

use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspector")
 */
class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="app_feedback")
     */
    public function FeedbackList(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(Feedback::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('inspector/templates/feedback.html.twig', [
            'controller_name' => 'FeedbackController',
            'pagination' => $pagination
        ]);

    }
    /**
     * @Route("/feedback-view/{id}", name="app_feedback_view")
     */
    public function FeedbackView(int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $feedback = $entity_manager->getRepository(Feedback::class)->find($id);

        return $this->render('inspector/templates/feedbackView.html.twig', [
            'controller_name' => 'FeedbackController',
            'feedback' => $feedback
        ]);

    }
}
