<?php

namespace App\Controller\API;

use App\Entity\PurchaseNote;
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
class ApiCertificateNotificationController extends AbstractController
{
    /**
     * @Route("/certificate-notification", name="app_api_certificate_notification")
     */
    public function getClusterByYear(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();
        $contractCertificate = $user->getUserInfo()->getContractCertifications();
        if(count($contractCertificate))
            $contractCertificate = $contractCertificate->last()->getStatus();
        else
            $contractCertificate = null;

        $jsonContent = json_encode($contractCertificate, JSON_UNESCAPED_UNICODE);;


        return new JsonResponse($jsonContent);
    }
}
