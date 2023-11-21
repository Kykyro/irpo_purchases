<?php

namespace App\Controller;

use App\Entity\ClusterZone;
use App\Entity\PhotosVersion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpectatorReadinessMapController extends AbstractController
{
    /**
     * @Route("/spectator/readiness-map/{id}", name="app_spectator_readiness_map")
     */
    public function index(EntityManagerInterface $em, $id): Response
    {

        $user = $em->getRepository(User::class)->find($id);



        return $this->render('spectator_readiness_map/index.html.twig', [
            'controller_name' => 'SpectatorReadinessMapController',
            'user' => $user
        ]);
    }

    /**
     * @Route("/spectator/readiness-map/zone/{id}", name="app_spectator_readiness_map_zone")
     */
    public function zone(EntityManagerInterface $em, $id, Request $request, PaginatorInterface $paginator): Response
    {

        $zone = $em->getRepository(ClusterZone::class)->find($id);

        $repair = $zone->getZoneRepair();
        $query = $em->getRepository(PhotosVersion::class)
            ->createQueryBuilder('p')
            ->andWhere('p.repair = :repair')
            ->orderBy('p.id', 'DESC')
            ->setParameter('repair', $repair)
            ->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );




        return $this->render('spectator_readiness_map/zone.html.twig', [
            'controller_name' => 'SpectatorReadinessMapController',
            'zone' => $zone,
            'pagination' => $pagination,
        ]);
    }

}
