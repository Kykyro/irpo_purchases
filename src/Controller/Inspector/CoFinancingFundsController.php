<?php

namespace App\Controller\Inspector;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspector")
 */
class CoFinancingFundsController extends AbstractController
{
    /**
     * @Route("/co-financing-funds/{id}", name="app_inspector_co_financing_funds")
     */
    public function index(EntityManagerInterface $em, Request $request, int $id, PaginatorInterface $paginator): Response
    {
        $user = $em->getRepository(User::class)->find($id);




        return $this->render('co_financing_funds/index.html.twig', [
            'controller_name' => 'CoFinancingFundsController',
            'user' => $user
        ]);
    }
}
