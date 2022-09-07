<?php

namespace App\Controller;

use App\Entity\Regions;
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
class ApiMapController extends AbstractController
{
    /**
     * @Route("/map", name="app_api_map")
     */
    public function getMap(): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

//        dd($map = $this->getDoctrine()->getRepository(Regions::class)->find(1));
        $map = $this->getDoctrine()->getRepository(Regions::class)->findAll();
//        dd($map);
        $jsonContent = $serializer->serialize($map, 'json');


        return new JsonResponse($jsonContent);
    }
}
