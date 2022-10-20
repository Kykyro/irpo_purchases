<?php

namespace App\Controller\Landing;

use App\Entity\Industry;
use App\Entity\InfrastructureSheetFiles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        if ($type === 'cluster_IS'){
            $title = 'Инфраструктурные листы (Кластеры)';
        }
        else if($type === 'workshops_IS'){
            $title = 'Инфраструктурные листы  (Мастерские)';
        }
        else{
            return $this->redirectToRoute('app_start_landing');
        }

        $pageLimit = 10;
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add("search", TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,

            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $query = $em->getRepository(InfrastructureSheetFiles::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->where('a.type LIKE :type')
            ->setParameter('type', "%$type%")
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            if($form_data['search'] !== null){
                $searchText = $form_data['search'];
                $query = $em->getRepository(InfrastructureSheetFiles::class)
                    ->createQueryBuilder('a')
                    ->andWhere('a.name LIKE :search')
                    ->andWhere('a.type LIKE :type')
                    ->setParameter('search', "%$searchText%")
                    ->setParameter('type', "%$type%")
                    ->orderBy('a.id', 'ASC')
                    ->getQuery();

                $pageLimit = 100;
            }
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
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
