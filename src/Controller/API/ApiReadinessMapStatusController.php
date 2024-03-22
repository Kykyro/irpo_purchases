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
class ApiReadinessMapStatusController extends AbstractController
{
    /**
     * @Route("/rm-status", name="app_api_rm_status")
     */
    public function getClusterByYear(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $ReadinessMapChecks = $user->getReadinessMapChecks()->last();

        if($ReadinessMapChecks)
        {
            $status = $ReadinessMapChecks->getStatus()->last();
            if($status)
            {
                $result = $status->getStatus();
            }
            else
            {
                $result = null;
            }

        }
        else
            $result = null;

        return new JsonResponse($result);
    }
}
