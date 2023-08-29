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
 * @Route("/roiv")
 */
class ROIVClusterListController extends AbstractController
{
    /**
     * @Route("/cluster-list", name="app_roiv_cluster_list")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $roiv = $this->getUser();
        $clusters = $em->getRepository(User::class)
            ->findAllClusterByRegion($roiv->getUserInfo()->getRfSubject());




        return $this->render('roiv/clusterList.html.twig', [
            'clusters' => $clusters
        ]);
    }

}
