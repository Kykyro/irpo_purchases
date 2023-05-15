<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CertificateController extends AbstractController
{
    /**
     * @Route("/certificate", name="app_certificate")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $role = "%%";
        $query = $entityManager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role or a.roles LIKE :role_2')
            ->andWhere('uf.year > :_year')
            ->setParameter('role', "%ROLE_REGION%")
            ->setParameter('role_2', "%ROLE_SMALL_CLUSTERS%")
            ->setParameter('_year', 2021);

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('certificate/index.html.twig', [
            'controller_name' => 'CertificateController',
            'pagination' => $pagination
        ]);
    }
}
