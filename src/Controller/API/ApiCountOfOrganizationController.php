<?php

namespace App\Controller\API;


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
class ApiCountOfOrganizationController extends AbstractController
{
    /**
     * @Route("/count-of-organization/{region}", name="app_api_count_of_organization")
     */
    public function getCountOfOrganization(int $region): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $entity_manager = $this->getDoctrine()->getManager();
        $role = 'ROLE_REGION';
       ;

        $organization = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role')
            ->andWhere('uf.rf_subject = :region')
            ->setParameter('role', "%$role%")
            ->setParameter('region', "$region")
            ->getQuery()
            ->getResult();

        $jsonContent = $serializer->serialize(count($organization), 'json');


        return new JsonResponse($jsonContent);
    }
}
