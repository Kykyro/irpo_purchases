<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfrastructureListCheckerController extends AbstractController
{
    /**
     * @Route("/admin/infrastructure-list-checker", name="app_infrastructure_list_checker")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role_1 OR a.roles LIKE :role_2')
            ->andWhere('uf.year > :year')

            ->setParameter('role_1', "%REGION%")
            ->setParameter('role_2', "%SMALL_CLUSTERS%")
            ->setParameter('year', "2021")

            ->getQuery()
            ->getResult();

        $zone_arr = [];
        foreach ($users as $user)
        {
            $addresses = $user->getClusterAddresses();
            foreach ($addresses as $address) {
                $zones = $address->getClusterZones();
                foreach ($zones as $zone)
                {
                    if(count($zone->getZoneInfrastructureSheets()) == 0
                    and $zone->getType()->getName() == 'Зона по видам работ')
                    {
                        array_push($zone_arr, $zone);
//                        dd();
                    }
                }
            }
        }


        return $this->render('admin/infrastructure_list_checker/index.html.twig', [
            'controller_name' => 'InfrastructureListCheckerController',
            'zones' => $zone_arr
        ]);
    }
}
