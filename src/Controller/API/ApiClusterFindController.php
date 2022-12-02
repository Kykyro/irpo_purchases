<?php

namespace App\Controller\API;

use App\Entity\Regions;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api")
 */
class ApiClusterFindController extends AbstractController
{
    /**
     * @Route("/cluster/{year}", name="app_api_cluster_by_year")
     */
    public function getClusterByYear(int $year): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $entity_manager = $this->getDoctrine()->getManager();
        $role = 'ROLE_REGION';

        $cluster = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role')
            ->andWhere('uf.year = :region')
            ->setParameter('role', "%$role%")
            ->setParameter('region', "$year")
            ->getQuery()
            ->getResult();

        $jsonContent = $serializer->serialize($cluster, 'json');


        return new JsonResponse($jsonContent);
    }
}
