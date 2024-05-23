<?php

namespace App\Controller\Admin;

use App\Entity\ContractCertification;
use App\Entity\ReadinessMapCheck;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReadinessMapCheckController extends AbstractController
{
    /**
     * @Route("/admin/readiness-map-check", name="app_admin_readiness_map_check")
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

        $form = $this->createFormBuilder()
            ->add('search', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'   => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
            $search = $data['search'];
            $query = $query
                ->andWhere('uf.educational_organization LIKE :search')
                ->setParameter('search', "%$search%");
        }


        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('admin/admin_rediness_map_check/index.html.twig', [
            'controller_name' => 'AdminContractCertificateController',
            'users' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/readiness-map-check/create/{id}", name="app_admin_readiness_map_check_create")
     */
    public function create(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if(count($user->getReadinessMapChecks()) == 0)
        {
            $readinessMapCheck = new ReadinessMapCheck($user);
            $em->persist($readinessMapCheck);
            $em->persist($user);
            $em->flush();
        }
        $route = $request->headers->get('referer');

        return $this->redirect($route);

    }
    /**
     * @Route("/admin/readiness-map-check/refresh/{id}", name="app_admin_readiness_map_check_refresh")
     */
    public function refresh(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        $userInfo = $user->getUserInfo();


        $userInfo->setReadinessMapChecksRefresh(!$userInfo->isReadinessMapChecksRefresh());

        $em->persist($userInfo);
        $em->flush();

        $route = $request->headers->get('referer');
        return $this->redirect($route);

    }
}
