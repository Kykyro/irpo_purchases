<?php

namespace App\Controller\API;

use App\Entity\Regions;
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

/**
 * @Route("/api")
 */
class ApiUserController extends AbstractController
{
    /**
     * @Route("/user_orgs/{id}", name="app_api_user_orgs")
     */
    public function getOrgs(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

//        dd($map = $this->getDoctrine()->getRepository(Regions::class)->find(1));
        $orgs = $em->getRepository(UserInfo::class)->find($id)->getListOfEdicationOrganization();
//        dd($map);
        $jsonContent = $serializer->serialize($orgs, 'json');


        return new JsonResponse($jsonContent);
    }

    /**
     * @Route("/user_orgs_edit/{id}", name="app_api_user_orgs_edit")
     */
    public function setOrgs(EntityManagerInterface $em, Request $request, int $id)
    {
        $submittedToken = $request->request->get('token');
        $userInfo = $em->getRepository(UserInfo::class)->find($id);

        if ($this->isCsrfTokenValid('org-edit', $submittedToken)) {

            $userInfo->setListOfEdicationOrganization($request->request->get('orgs'));
            $em->persist($userInfo);
            $em->flush();
//            dd($request->request);
        }

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}
