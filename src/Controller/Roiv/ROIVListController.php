<?php

namespace App\Controller\Roiv;

use App\Entity\Building;
use App\Entity\ProfEduOrg;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function roivOrgs(EntityManagerInterface $em, int $id): Response
    {
        $roiv = $em->getRepository(User::class)->find($id);
        $orgs = $em->getRepository(ProfEduOrg::class)
            ->findAllByRegion($roiv->getUserInfo()->getRfSubject()->getId());




        return $this->render('analyst/roiv_list/orgs.html.twig', [
            'controller_name' => 'ROIVListController',
            'orgs' => $orgs,
            'roiv' => $roiv
        ]);
    }
}
