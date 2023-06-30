<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\ProfEduOrg;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roiv")
 */
class RoivController extends AbstractController
{
    /**
     * @Route("/list", name="app_roiv")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $orgs = $em->getRepository(ProfEduOrg::class)
            ->findAllByRegion($user->getUserInfo()->getRfSubject()->getId());




        return $this->render('roiv/index.html.twig', [
            'controller_name' => 'ROIVListController',
            'orgs' => $orgs,
        ]);
    }
    /**
     * @Route("/add_org", name="app_roiv_add_org")
     * @Route("/edit_org/{id}", name="app_roiv_edit_org")
     */
    public function addOrg(EntityManagerInterface $em, int $id=null, Request $request): Response
    {
        if($id)
        {
            $org = $em->getRepository(ProfEduOrg::class)->find($id);
        }
        else
        {
            $org = new ProfEduOrg();
            $org->setRegion($this->getUser()->getUserInfo()->getRfSubject());
        }

        $form = $this->createFormBuilder($org)
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Полное наименование профессиональной образовательной организации'
            ])
            ->add('shortName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Сокращенное наименование профессиональной образовательной организации'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and  $form->isValid())
        {

            $em->persist($org);
            $em->flush();
            return $this->redirectToRoute('app_roiv');
        }




        return $this->render('roiv/addOrg.html.twig', [
            'controller_name' => 'ROIVListController',
            'form' => $form->createView(),
        ]);
    }
//    /**
//     * @Route("/building-list/{id}", name="app_roiv_building_list")
//     */
//    public function buildingList(EntityManagerInterface $em, int $id): Response
//    {
//        $buildings = $em->getRepository(Building::class)
//            ->findAllByRegion($user->getUserInfo()->getRfSubject()->getId());

//        return $this->render('roiv/buildingList.html.twig', [
//            'controller_name' => 'ROIVListController',
//            'orgs' => $orgs,
//        ]);
//    }
}
