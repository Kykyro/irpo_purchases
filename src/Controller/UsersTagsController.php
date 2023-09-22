<?php

namespace App\Controller;

use App\Entity\User;
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
 *
 */
class UsersTagsController extends AbstractController
{
    /**
     * @Route("/inspector/users-tags", name="app_users_tags")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $query = $entityManager->getRepository(UserTags::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
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
     * @Route("/inspector/users-tags/add", name="app_users_tags_add")
     * @Route("/inspector/users-tags/edit/{id}", name="app_users_tags_edit")
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
        $errors = null;

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
            $findTag = $entityManager->getRepository(UserTags::class)
                ->findBy([
                    'tag' => $tag->getTag()
                ]);
            if(!$findTag)
            {
                $entityManager->persist($tag);
                $entityManager->flush();

                return $this->redirectToRoute('app_users_tags');
            }
            else
            {
                $errors = [
                  'name' => 'Такой тег уже существует!'
                ];

                return $this->render('users_tags/add.html.twig', [
                    'controller_name' => 'UsersTagsController',
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);
            }
        }

        return $this->render('users_tags/add.html.twig', [
            'controller_name' => 'UsersTagsController',
            'form' => $form->createView(),
            'errors' => $errors,
        ]);

    }

    /**
     * @Route("/inspector/users-tags/remove/{id}", name="app_users_tags_remove")
     */
    public function remove(Request $request, $id, EntityManagerInterface $em)
    {
        $route = $request->headers->get('referer');
        $tag = $em->getRepository(UserTags::class)->find($id);
        $em->remove($tag);
        $em->flush();

        return $this->redirect($route);
    }

    /**
     * @Route("/inspector/users-tags/save/{id}", name="app_users_tags_save")
     */
    public function save(Request $request, $id, EntityManagerInterface $em){

        $route = $request->headers->get('referer');
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('tags-save', $submittedToken)) {
            $user = $em->getRepository(User::class)->find($id);
            $tagsIds = $request->request->get('tags');
            $tagsIds = explode(",", $tagsIds);
            $validTags = [];
            foreach ($user->getUserTags() as $tag)
            {
                if(in_array($tag->getId(), $tagsIds))
                {
                    array_push($validTags, $tag);
                }
                else
                {
                    $user->removeUserTag($tag);
                }
            }
            foreach ($tagsIds as $tagId)
            {
                $tag = $em->getRepository(UserTags::class)->find($tagId);
                if($tag)
                    $user->addUserTag($tag);
            }
            $em->persist($user);
            $em->flush();


            return $this->redirect($route);
        }
        return $this->redirect($route);
    }
}
