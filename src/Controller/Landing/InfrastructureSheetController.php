<?php

namespace App\Controller\Landing;

use App\Entity\Industry;
use App\Entity\InfrastructureSheetFiles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Proxies\__CG__\App\Entity\UGPS;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/infrastructure-sheets/{type}", name="app_infrastructure_sheets")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator, string $type): Response
    {
        $data = [];

        if ($type === 'cluster_IS' || $type === 'workshops_IS'){
            if( $type === 'workshops_IS')
                $title = 'Типовые инфраструктурные листы для создания современных мастерских (учебно-производственных участков)';
            else
                $title = 'Типовые инфраструктурные листы для оснащения зон по видам работ образовательно-производственных центров (кластеров)';
            $form = $this->createFormBuilder($data)
            ->add("search", TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,

            ])
            ->add("UGPS", EntityType::class,[
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => UGPS::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ugps')
                        ->orderBy('ugps.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
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
            ->setMethod('GET')
            ->getForm();
        }
//        else if($type === 'workshops_IS'){
//            $title = 'Типовые инфраструктурные листы для создания современных мастерских (учебно-производственных участков)';
//            $form = $this->createFormBuilder($data)
//            ->add("search", TextType::class, [
//                'attr' => ['class' => 'form-control'],
//                'required'   => false,
//
//            ])
//
//            ->add("submit", SubmitType::class)
//            ->setMethod('GET')
//            ->getForm();
//        }
        else{
            return $this->redirectToRoute('app_start_landing');
        }


        if($request->query->has('page')){
            $page = $request->query->get('page');
        }
        else{
            $page = 1;
        }
        $pageLimit = 10;
       
        

        $form->handleRequest($request);

        $query = $em->getRepository(InfrastructureSheetFiles::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->where('a.type LIKE :type')
            ->andWhere('a.hide LIKE :hide')
            ->setParameter('type', "%$type%")
            ->setParameter('hide', false)
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $searchText = $form_data['search'];
//            if ($type === 'cluster_IS'){
                $UGPS = $form_data['UGPS'];
                $industry = $form_data['industry'];
                $query = $em->getRepository(InfrastructureSheetFiles::class)
                    ->createQueryBuilder('a')
                    ->andWhere('a.name LIKE :search')
                    ->andWhere('a.type LIKE :type')
                    ->andWhere('a.hide LIKE :hide')
                    ->setParameter('search', "%$searchText%")
                    ->setParameter('type', "%$type%")
                    ->setParameter('hide', false);
                    
                if($industry !== null){
                    $query = $query
                        ->andWhere('a.industry = :industry')
                        ->setParameter('industry', $industry);
                }
                if($UGPS !== null){
                    $query = $query
                        ->andWhere('a.UGPS = :UGPS')
                        ->setParameter('UGPS', $UGPS);
                }
                
                $query = $query
                    ->orderBy('a.id', 'ASC')
                    ->getQuery();
                
//            }
//            else{
//                $query = $em->getRepository(InfrastructureSheetFiles::class)
//                    ->createQueryBuilder('a')
//                    ->andWhere('a.name LIKE :search')
//                    ->andWhere('a.type LIKE :type')
//                    ->setParameter('search', "%$searchText%")
//                    ->setParameter('type', "%$type%")
//                    ->orderBy('a.id', 'ASC')
//                    ->getQuery();
//            }
            
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            $pageLimit /*limit per page*/
        );

        return $this->render('infrastructure_sheet/index.html.twig', [
            'controller_name' => 'InfrastructureSheetController',
            'form' => $form->createView(),
            'pagination' => $pagination,
            'type' => $type,
            'title' => $title
        ]);
    }
}
