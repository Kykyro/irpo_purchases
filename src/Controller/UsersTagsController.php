<?php

namespace App\Controller;

use App\Entity\UserTags;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersTagsController
 * @package App\Controller
 * @Route("/admin")
 */
class UsersTagsController extends AbstractController
{
    /**
     * @Route("/users-tags", name="app_users_tags")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $query = $entityManager->getRepository(UserTags::class)
            ->createQueryBuilder('a')
            ;

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );



        return $this->render('users_tags/index.html.twig', [
            'controller_name' => 'UsersTagsController',
            'pagination' => $pagination
        ]);
    }
    /**
     * @Route("/users-tags/add", name="app_users_tags_add")
     * @Route("/users-tags/edit/{id}", name="app_users_tags_edit")
     */
    public function add(Request $request, EntityManagerInterface $entityManager, int $id = null): Response
    {

        if($id)
        {
            $tag = $entityManager->getRepository(UserTags::class)
                ->find($id);
        }
        else{
            $tag = new UserTags();
        }


        $form = $this->createFormBuilder($tag)
            ->add('tag', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Тег'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {

            $entityManager->persist($tag);
            $entityManager->flush();
        }


        return $this->render('users_tags/add.html.twig', [
            'controller_name' => 'UsersTagsController',
            'form' => $form->createView()
        ]);
    }
}
