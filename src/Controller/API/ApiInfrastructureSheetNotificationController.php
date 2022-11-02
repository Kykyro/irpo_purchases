<?php

namespace App\Controller\API;

use App\Entity\InfrastructureSheetRegionFile;
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
class ApiInfrastructureSheetNotificationController extends AbstractController
{
    /**
     * @Route("/infrastructure-sheet-notification", name="app_api_infrastructure_sheet_notification")
     */
    public function getFeedbackUnviewed(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $infrastructureSheetFile = $this->getDoctrine()->getRepository(InfrastructureSheetRegionFile::class)->findBy([
            'isViewed' => false,
            'user' => $this->getUser()->getId()
        ]);
        $jsonContent = $serializer->serialize(count($infrastructureSheetFile), 'json');


        return new JsonResponse($jsonContent);
    }
}
