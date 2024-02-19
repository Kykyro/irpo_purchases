<?php

namespace App\Controller\x_api;

use App\Entity\Employers;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class XApiController extends AbstractController
{
    /**
     * @Route("/x-api", name="api_x_main")
     */
    public function index(): Response
    {
        $data = [
            'message' => 'Добро пожаловать!'
        ];

        return new JsonResponse($data, Response::HTTP_OK);

    }

    /**
     * @Route("/x-api/employers", name="api_x_employers")
     */
    public function getEmployers(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, SerializerInterface $serializer): Response
    {
        $query = $em->getRepository(Employers::class)
            ->createQueryBuilder('e')
            ->leftJoin('e.userInfos', 'uf')
            ->andWhere('uf.year > :year')
            ->setParameter('year', 2021)
            ->orderBy('e.name', 'ASC')
            ;

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            50 /*limit per page*/
        );

        $jsonContent =  $serializer->serialize($pagination->getItems(), 'json',['groups' => ['api']]);
        $data = [
          'message' =>   $jsonContent
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
