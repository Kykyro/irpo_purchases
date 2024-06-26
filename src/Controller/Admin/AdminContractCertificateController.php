<?php

namespace App\Controller\Admin;

use App\Entity\ContractCertification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContractCertificateController extends AbstractController
{
    /**
     * @Route("/admin/contract/certificate", name="app_admin_contract_certificate")
     */
    public function index(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role_1 OR a.roles LIKE :role_2 OR a.roles LIKE :role_3')
            ->andWhere('uf.accessToPurchases = :access')
            ->setParameter('role_1', "%REGION%")
            ->setParameter('role_2', "%SMALL_CLUSTERS%")
            ->setParameter('role_3', "%ROLE_BAS%")
            ->setParameter('access', true)
            ->orderBy('a.id', 'DESC')
                ;



        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('admin/admin_contract_certificate/index.html.twig', [
            'controller_name' => 'AdminContractCertificateController',
            'users' => $pagination
        ]);
    }

    /**
     * @Route("/admin/contract/certificate/create/{id}", name="app_admin_contract_certificate_create")
     */
    public function create(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        $userInfo = $user->getUserInfo();

        if(count($userInfo->getContractCertifications()) == 0)
        {
            $contractCertification = new ContractCertification($userInfo);
            $em->persist($contractCertification);
            $em->flush();
        }
        $route = $request->headers->get('referer');

        return $this->redirect($route);

    }
}
