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
class BasCuratorController extends AbstractController
{
    /**
     * @Route("/all", name="app_bas_curator_all")
     */
    public function index(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {

        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :val')
            ->setParameter('val', "%ROLE_BAS%")
            ->orderBy('u.id', 'ASC')

        ;

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );



        return $this->render('bas_curator/index.html.twig', [
            'controller_name' => 'BasCuratorController',
            'bas' => $pagination
        ]);
    }

    /**
     * @Route("/info/{id}", name="app_bas_curator_info")
     */
    public function info(EntityManagerInterface $em, Request $request, $id): Response
    {
        $bas = $em->getRepository(User::class)->find($id);



        return $this->render('BAS/profile.html.twig', [
            'controller_name' => 'BasCuratorController',
            'user_info' => $bas->getUserInfo(),
            'user' => $bas,
        ]);
    }
}
