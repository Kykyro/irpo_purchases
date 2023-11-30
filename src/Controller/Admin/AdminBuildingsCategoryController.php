<?php

namespace App\Controller\Admin;

use App\Entity\BuildingCategory;
use App\Entity\BuildingPriority;
use App\Entity\BuildingType;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Form\RegistrationUserInfoFormType;
use App\Form\UserEditFormType;
use App\Form\RegistrationFormType;
use App\Form\UserPasswordEditFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 *
 * @Route("/admin")
 *
 */
class AdminBuildingsCategoryController extends AbstractController
{
    /**
     * @Route("/buildings-category", name="app_admin_building_category")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(BuildingCategory::class)->findAll();
        $priority = $em->getRepository(BuildingPriority::class)->findAll();
        return $this->render('admin/templates/buildings_category.html.twig', [
            'controller_name' => 'AdminController',
            'category' => $category,
            'priority' => $priority,
        ]);
    }
    /**
     * @Route("/buildings-add", name="app_admin_building_category_add")
     * @Route("/buildings-edit/{id}", name="app_admin_building_category_edit")
     */
    public function editCategory(EntityManagerInterface $em, int $id=null, Request $request): Response
    {
        if($id)
        {
            $category = $em->getRepository(BuildingCategory::class)->find($id);
        }
        else
        {
            $category = new BuildingCategory();
        }

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Название'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_admin_building_category');
        }


        return $this->render('admin/templates/buildings_category_edit.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
            'title' => 'Категории зданий',
        ]);
    }


    /**
     *
     * @Route("/buildings-remove/{id}", name="app_admin_building_category_remove")
     */
    public function removeCategory(EntityManagerInterface $em, int $id)
    {
        $category = $em->getRepository(BuildingCategory::class)->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_admin_building_category');
    }


    /**
     * @Route("/priority-add", name="app_admin_building_priority_add")
     * @Route("/priority-edit/{id}", name="app_admin_building_priority_edit")
     */
    public function editPriority(EntityManagerInterface $em, int $id=null, Request $request): Response
    {
        if($id)
        {
            $priority = $em->getRepository(BuildingPriority::class)->find($id);
        }
        else
        {
            $priority = new BuildingPriority();
        }

        $form = $this->createFormBuilder($priority)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Название'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($priority);
            $em->flush();
            return $this->redirectToRoute('app_admin_building_category');
        }


        return $this->render('admin/templates/buildings_category_edit.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
            'title' => 'Приоритетность',
        ]);
    }

    /**
     *
     * @Route("/priority-remove/{id}", name="app_admin_building_priority_remove")
     */
    public function removePriority(EntityManagerInterface $em, int $id)
    {
        $priority = $em->getRepository(BuildingPriority::class)->find($id);

        $em->remove($priority);
        $em->flush();

        return $this->redirectToRoute('app_admin_building_category');
    }


}
