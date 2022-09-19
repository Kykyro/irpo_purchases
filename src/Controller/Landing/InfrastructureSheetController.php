<?php

namespace App\Controller\Landing;

use App\Entity\Industry;
use App\Entity\InfrastructureSheetFiles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/infrastructure-sheets", name="app_infrastructure_sheets")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add("industry", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => Industry::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $query = $em->getRepository(InfrastructureSheetFiles::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            if($form_data['industry'] !== null){
                $query = $em->getRepository(InfrastructureSheetFiles::class)
                    ->createQueryBuilder('a')
                    ->where('a.industry = :industry')
                    ->setParameter('industry', $form_data['industry']->getId())
                    ->orderBy('a.id', 'ASC')
                    ->getQuery();
            }
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('infrastructure_sheet/index.html.twig', [
            'controller_name' => 'InfrastructureSheetController',
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
