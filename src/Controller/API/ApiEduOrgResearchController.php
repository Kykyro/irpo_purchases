<?php

namespace App\Controller\API;

use App\Entity\Feedback;
use App\Entity\ProfEduOrg;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class ApiEduOrgResearchController extends AbstractController
{
    /**
     * @Route("/edu-org-research/{region}/{search}", name="app_api_edu_org_research")
     */
    public function getFeedbackUnviewed(SerializerInterface $serializer, Request $request, EntityManagerInterface $em, $region, $search): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $eduOrgs = $em->getRepository(ProfEduOrg::class)
            ->createQueryBuilder('o')
            ->andWhere('o.region = :region')
            ->andWhere('o.fullName LIKE :fullName')
            ->setParameter('fullName', "%$search%")
            ->setParameter('region', $region)
            ->getQuery()
            ->getResult()
            ;

        $jsonContent = $serializer->serialize($eduOrgs, 'json', ['groups' => ['search']]);


        return new JsonResponse($jsonContent);
    }
}
