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
class ApiNewPurchasesNoteController extends AbstractController
{
    /**
     * @Route("/purchase-notes", name="app_api_purchase_notes")
     */
    public function getClusterByYear(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $entity_manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $note = $entity_manager->getRepository(PurchaseNote::class)
            ->createQueryBuilder('n')
            ->leftJoin('n.purchase', 'p')
            ->andWhere('p.user = :user')
            ->andWhere('n.isRead = :isRead')
            ->setParameter('user', $user)
            ->setParameter('isRead', false)
            ->getQuery()
            ->getResult();

        count($note);


        $jsonContent = $serializer->serialize(count($note), 'json');


        return new JsonResponse($jsonContent);
    }
}
