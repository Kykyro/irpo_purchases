<?php

namespace App\Controller\SMI;

use App\Entity\RfSubject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/smi")
 */
class SmiController extends AbstractController
{
    /**
     * @Route("/clusters", name="app_smi")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $role = 'ROLE_REGION';

        $form_data = [];
        $form = $this->createFormBuilder($form_data)
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
            ->add("year", ChoiceType::class,[
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'choices'  => [
                    '2022' => 2022,
                    '2023' => 2023,
                    '2024' => 2024,

                ],

            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mt-3'
                ],
                'label' => 'Найти'
            ])
            ->setMethod('GET')
            ->getForm();

        $query = $em->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('uf.year > :_year')
            ->setParameter('_year', 2021);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();

            if($form_data['rf_subject'] !== null){
                $region = $form_data['rf_subject'];
                $query = $query

                    ->andWhere('uf.rf_subject = :rf_subject')
                    ->setParameter('rf_subject', $region);
            }
            if($form_data['year'] !== null){
                $year = $form_data['year'];
                $query = $query

                    ->andWhere('uf.year = :year')
                    ->setParameter('year', $year);
            }
        }

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        return $this->render('smi/index.html.twig', [
            'controller_name' => 'SmiController',

            'pagination' => $pagination,
            'form' => $form->createView(),

        ]);
    }
}
