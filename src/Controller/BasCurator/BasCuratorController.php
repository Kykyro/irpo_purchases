<?php

namespace App\Controller\BasCurator;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/bas-curator")
 */
class BasCuratorController extends AbstractController
{
    /**
     * @Route("/all", name="app_bas_curator_all")
     */
    public function index(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {

        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->andWhere('uf.year > :year')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :val')
            ->setParameter('val', "%ROLE_BAS%")
            ->setParameter('year', 2022)
            ->orderBy('u.id', 'ASC')

        ;

        $form = $this->createFormBuilder()
            ->add("rf_subject", EntityType::class, [
                'attr' => ['class' => 'form-control select2'],
                'required'   => false,
                'class' => RfSubject::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
//            ->add("year", ChoiceType::class,[
//                'attr' => ['class' => 'form-control'],
//                'required'   => false,
//                'choices'  => [
//                    '2022' => 2022,
//                    '2023' => 2023,
//                    '2024' => 2024,
//
//                ],
//
//            ])
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            if($form_data['rf_subject'] !== null){
                $region = $form_data['rf_subject'];
                $query = $query

                    ->andWhere('uf.rf_subject = :rf_subject')
                    ->setParameter('rf_subject', $region);
            }
        }

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );



        return $this->render('bas_curator/index.html.twig', [
            'controller_name' => 'BasCuratorController',
            'bas' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/info/{id}", name="app_bas_curator_info")
     */
    public function info(EntityManagerInterface $em, Request $request, $id): Response
    {
        $bas = $em->getRepository(User::class)->find($id);



        return $this->render('BAS/profile.html.twig', [
            'controller_name' => 'BasCuratorController',
            'user_info' => $bas->getUserInfo(),
            'user' => $bas,
        ]);
    }

    /**
     * @Route("/info/edit-funds/{id}", name="app_bas_curator_edit_funds")
     */
    public function editFunds(EntityManagerInterface $em, Request $request, $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('edit-funds', $submittedToken))
        {
            $userInfo = $user->getUserInfo();
            $FedFundsGrant = $request->request->get('FedFundsGrant');
            $RegionFundsGrant = $request->request->get('RegionFundsGrant');
            $userInfo->setFedFundsGrant($FedFundsGrant);
            $userInfo->setRegionFundsGrant($RegionFundsGrant);

            $em->persist($userInfo);
            $em->flush();
        }


        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }


}
