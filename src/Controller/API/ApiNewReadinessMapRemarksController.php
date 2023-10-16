<?php

namespace App\Controller\API;

use App\Entity\PurchaseNote;
use App\Entity\Regions;
use App\Entity\User;
use App\Entity\ZoneRemark;
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
class ApiNewReadinessMapRemarksController extends AbstractController
{
    /**
     * @Route("/zones-remarks", name="app_api_zones_remakrs")
     */
    public function getClusterByYear(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $entity_manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $count = 0;

        foreach($user->getClusterAddresses() as $address)
        {
            foreach ($address->getClusterZones() as $zones)
            {
                $count += count($zones->getZoneRemarks());
            }
        }



        $jsonContent = $serializer->serialize($count, 'json');


        return new JsonResponse($jsonContent);
    }
}
