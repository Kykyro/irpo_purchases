<?php

namespace App\Controller\API;

use App\Entity\Feedback;
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
class ApiFeedbackController extends AbstractController
{
    /**
     * @Route("/feedback-unviewed", name="app_api_feedback")
     */
    public function getFeedbackUnviewed(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->findBy([
            'isViewed' => false
        ]);
        $jsonContent = $serializer->serialize(count($feedback), 'json');


        return new JsonResponse($jsonContent);
    }
}
