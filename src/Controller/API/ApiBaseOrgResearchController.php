<?php

namespace App\Controller\API;

use App\Entity\Feedback;
use App\Entity\ProfEduOrg;
use App\Entity\UserInfo;
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
class ApiBaseOrgResearchController extends AbstractController
{
    /**
     * @Route("/base-org-research/{search}", name="app_api_base_org_research")
     */
    public function getBaseOrg(SerializerInterface $serializer, Request $request, EntityManagerInterface $em, $search): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $eduOrgs = $em->getRepository(UserInfo::class)
            ->createQueryBuilder('uf')
            ->andWhere('uf.educational_organization LIKE :org')
            ->setParameter('org', "%$search%")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;

        $jsonContent = $serializer->serialize($eduOrgs, 'json', ['groups' => ['search']]);


        return new JsonResponse($jsonContent);
    }
}
