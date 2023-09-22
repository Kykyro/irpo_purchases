<?php

namespace App\Controller\API;

use App\Entity\PurchaseNote;
use App\Entity\Regions;
use App\Entity\User;
use App\Entity\UserTags;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
class ApiUserTagsController extends AbstractController
{
    /**
     * @Route("/get-user-tag/{id}", name="app_api_get_user_tags")
     */
    public function getClusterByYear($id, SerializerInterface $serializer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);

        $jsonContent = $serializer->serialize($user->getUserTags(), 'json', ['groups' => ['tag_json']]);

        return new JsonResponse($jsonContent);
    }

    /**
     * @Route("/get-all-user-tag", name="app_api_get_all_user_tags")
     */
    public function getAllTags(SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $entity_manager = $this->getDoctrine()->getManager();
        $tags = $entity_manager->getRepository(UserTags::class)->findAll();


        $jsonContent = $serializer->serialize($tags, 'json', ['groups' => ['tag_json']]);
        return new JsonResponse($jsonContent);
    }
}
