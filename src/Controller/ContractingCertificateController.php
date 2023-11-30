<?php

namespace App\Controller;

use App\Entity\ContractCertification;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractingCertificateController extends AbstractController
{
    /**
     * @Route("/admin/contracting-certificate", name="app_contracting_certificate")
     */
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftjoin('u.user_info', 'uf')
            ->andWhere('uf.year > :_year')
            ->andWhere('u.roles > :role_1')
            ->andWhere('u.roles > :role_2')
            ->setParameter('_year', 2021)
            ->setParameter('role_1', "%ROLE_REGION%")
            ->setParameter('role_2', "%ROLE_SMALL_CLUSTERS%")

        ;

//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $form_data = $form->getData();
//
//            if($form_data['rf_subject'] !== null){
//                $region = $form_data['rf_subject'];
//                $query = $query
//
//                    ->andWhere('uf.rf_subject = :rf_subject')
//                    ->setParameter('rf_subject', $region);
//            }
//            if($form_data['year'] !== null){
//                $year = $form_data['year'];
//                $query = $query
//
//                    ->andWhere('uf.year = :year')
//                    ->setParameter('year', $year);
//            }
//        }

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );


        return $this->render('admin/contracting_certificate/index.html.twig', [
            'controller_name' => 'ContractingCertificateController',
            'pagination' => $pagination
        ]);
    }
}
