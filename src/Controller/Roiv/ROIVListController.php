<?php

namespace App\Controller\Roiv;

use App\Entity\Building;
use App\Entity\ProfEduOrg;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//analyst
/**
 * @Route("/analyst")
 */
class ROIVListController extends AbstractController
{
    /**
     * @Route("/roiv-list", name="app_roiv_list")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)
            ->findAllROIV();




        return $this->render('analyst/roiv_list/index.html.twig', [
            'controller_name' => 'ROIVListController',
            'users' => $users,
        ]);
    }
    /**
     * @Route("/roiv-orgs/{id}", name="app_roiv_orgs")
     */
    public function roivOrgs(EntityManagerInterface $em, int $id, PaginatorInterface $paginator, Request $request): Response
    {
        $roiv = $em->getRepository(User::class)->find($id);


        $query = $em->getRepository(ProfEduOrg::class)
            ->createQueryBuilder('p')
            ->andWhere('p.region = :region')
            ->setParameter('region', $roiv->getUserInfo()->getRfSubject()->getId());




        $query = $query->orderBy('p.id', 'ASC')->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('analyst/roiv_list/orgs.html.twig', [
            'controller_name' => 'ROIVListController',
            'roiv' => $roiv,
            'pagination' => $pagination
        ]);
    }
}
