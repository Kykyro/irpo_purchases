<?php

namespace App\Controller\Admin;

use App\Entity\EquipmentType;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\WorkzoneEqupmentUnit;
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
class AdminInfrastructureSheetController extends AbstractController
{

    /**
     * @Route("/infrastructure-sheet", name="app_cluster_infrastructure_sheet_settings_menu")
     */
    public function adminInfrastructureSheet(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {

        return $this->render('/admin/infrastructure_sheet/menu.html.twig', [
            'controller_name' => 'AdminController',

        ]);
    }
    /**
     * @Route("/infrastructure-sheet/equipment-types", name="app_cluster_infrastructure_sheet_settings_equipment_types")
     */
    public function adminInfrastructureSheetEquipmentTypes(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $equpmentTypes = $em->getRepository(EquipmentType::class)->findAll();


        $equpmentType = new EquipmentType();
        $form = $this->createFormBuilder($equpmentType)
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование'
            ])
            ->add('type', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Тип'
            ])
            ->add('isHide', CheckboxType::class,[
                'attr' => [
                    'class' => ''
                ],
                'label' => 'Скрыть',
                'required' => false
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($equpmentType);
            $em->flush();

            return $this->redirect($request->getUri());
        }


        return $this->render('/admin/infrastructure_sheet/types.html.twig', [
            'controller_name' => 'AdminController',
            'types' => $equpmentTypes,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/infrastructure-sheet/change-hide/{id}", name="app_cluster_infrastructure_sheet_settings_change_hide")
     */
    public function changeHide(EntityManagerInterface $em, int $id)
    {
        $type = $em->getRepository(EquipmentType::class)->find($id);
        $type->setIsHide(!$type->isIsHide());

        $em->persist($type);
        $em->flush();

        return $this->redirectToRoute('app_cluster_infrastructure_sheet_settings_equipment_types');
    }

    /**
     * @Route("/infrastructure-sheet/equipment-units", name="app_cluster_infrastructure_sheet_settings_equipment_units")
     */
    public function adminInfrastructureSheetEquipmentUnits(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $equipmentUnits = $em->getRepository(WorkzoneEqupmentUnit::class)->findAll();


        $equipmentUnite = new WorkzoneEqupmentUnit();
        $form = $this->createFormBuilder($equipmentUnite)
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование'
            ])
            ->add('type', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Тип'
            ])
            ->add('isHide', CheckboxType::class,[
                'attr' => [
                    'class' => ''
                ],
                'label' => 'Скрыть',
                'required' => false
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($equipmentUnite);
            $em->flush();

            return $this->redirect($request->getUri());
        }


        return $this->render('/admin/infrastructure_sheet/units.html.twig', [
            'controller_name' => 'AdminController',
            'unites' => $equipmentUnits,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/infrastructure-sheet/change-hide-units/{id}", name="app_cluster_infrastructure_sheet_settings_change_hide_units")
     */
    public function changeHideUnits(EntityManagerInterface $em, int $id)
    {
        $type = $em->getRepository(WorkzoneEqupmentUnit::class)->find($id);
        $type->setIsHide(!$type->isIsHide());

        $em->persist($type);
        $em->flush();

        return $this->redirectToRoute('app_cluster_infrastructure_sheet_settings_equipment_units');
    }

    /**
     * @Route("/infrastructure-sheet/edit-units/{id}", name="app_cluster_infrastructure_sheet_settings_edit_units")
     */
    public function editUnits(Request $request, EntityManagerInterface $em, int $id)
    {
        $equipmentUnite = $em->getRepository(WorkzoneEqupmentUnit::class)->find($id);
        $form = $this->createFormBuilder($equipmentUnite)
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование'
            ])
            ->add('type', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Тип'
            ])
            ->add('isHide', CheckboxType::class,[
                'attr' => [
                    'class' => ''
                ],
                'label' => 'Скрыть',
                'required' => false
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($equipmentUnite);
            $em->flush();

            return $this->redirectToRoute('app_cluster_infrastructure_sheet_settings_equipment_units');
        }


        return $this->render('/admin/infrastructure_sheet/edit.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/infrastructure-sheet/edit-types/{id}", name="app_cluster_infrastructure_sheet_settings_edit_types")
     */
    public function editTypes(Request $request, EntityManagerInterface $em, int $id)
    {
        $equipmentUnite = $em->getRepository(EquipmentType::class)->find($id);
        $form = $this->createFormBuilder($equipmentUnite)
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование'
            ])
            ->add('type', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Тип'
            ])
            ->add('isHide', CheckboxType::class,[
                'attr' => [
                    'class' => ''
                ],
                'label' => 'Скрыть',
                'required' => false
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($equipmentUnite);
            $em->flush();

            return $this->redirectToRoute('app_cluster_infrastructure_sheet_settings_equipment_types');
        }


        return $this->render('/admin/infrastructure_sheet/edit.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }

}
