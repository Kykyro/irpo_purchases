<?php

namespace App\Controller\BasCurator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/bas-curator")
 */
class BasCuratorComplexController extends AbstractController
{
    /**
     * @Route("/complex/{id}", name="app_bas_curator_complex")
     */
    public function index(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);




        return $this->render('bas_curator_complex/index.html.twig', [
            'controller_name' => 'BasCuratorController',

        ]);
    }

}
