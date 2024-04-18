<?php

namespace App\Controller\API;

use App\Entity\CofinancingScenarioFile;
use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
class ApiCofinancingInfoController extends AbstractController
{
    /**
     * @Route("/cofinancing-info/{id}", name="app_api_cofinancing_info")
     */
    public function getFeedbackUnviewed(EntityManagerInterface $em, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $em->getRepository(User::class)->find($id);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $feedback = $this->getDoctrine()->getRepository(CofinancingScenarioFile::class)->findByStatusAndUser($user, 'На проверке ');
        $jsonContent = $serializer->serialize(count($feedback), 'json');


        return new JsonResponse($jsonContent);
    }
}
