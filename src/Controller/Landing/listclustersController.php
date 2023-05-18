<?php

namespace App\Controller\Landing;

use App\Entity\RfSubject;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class listclustersController extends AbstractController
{
    /**
     * @Route("/listclusters", name="app_listclusters")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('search', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
            ])
            ->add("year", ChoiceType::class,[
                'attr' => ['class' => 'm-b select2 '],
                'required'   => false,
                'choices'  => [
                    '2022' => 2022,
                    '2023' => 2023,
                    '2024' => 2024,

                ],

            ])
            ->add("rf_subject", EntityType::class, [
                'attr' => ['class' => 'm-b select2  '],
                'required'   => false,
                'class' => RfSubject::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->setMethod('GET')
            ->getForm();

        if($request->query->has('page')){
            $page = $request->query->get('page');
        }
        else{
            $page = 1;
        }
        $pageLimit = 9;

        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'r')
            ->orderBy('r.name', 'ASC')
            ->andWhere('uf.year > :year')
            ->setParameter('year', 2021)
            ;

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
//            dd($data);
            if($data['rf_subject'])
                $query = $query
                    ->andWhere('r.id = :region')
                    ->setParameter('region', $data['rf_subject']->getId());
            if($data['year'])
                $query = $query
                    ->andWhere('uf.year = :year_search')
                    ->setParameter('year_search', $data['year']);
            $query = $query
                ->andWhere('
                uf.educational_organization LIKE :search
                or uf.Declared_industry LIKE :search
                or uf.cluster LIKE :search
                ')
                ->setParameter('search', "%".$data['search']."%")
                ;

        }

        $query = $query->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            $pageLimit /*limit per page*/
        );


        return $this->render('listclusters/index.html.twig', [
            'controller_name' => 'listclustersController',
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
