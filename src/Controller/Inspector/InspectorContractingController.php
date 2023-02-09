<?php

namespace App\Controller\Inspector;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class InspectorContractingController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorContractingController extends AbstractController
{
    /**
     * @Route("/contracting", name="app_inspector_contracting")
     */
    public function index(Request $request): Response
    {

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    '2023 год' => 2023,

                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Скачать'
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity_manger = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $users = $entity_manger->getRepository(User::class)->createQueryBuilder('u')
                ->leftJoin('u.user_info', 'uf')
                ->andWhere('u.roles LIKE :role')
                ->andWhere('uf.year = :year')
                ->setParameter('role', '%REGION%')
                ->setParameter('year', $data['year'])
                ->orderBy('u.id', 'ASC')
                ->getQuery()
                ->getResult();
            dd($users);

        }

        return $this->render('inspector_contracting/index.html.twig', [
            'controller_name' => 'InspectorContractingController',
            'form' => $form->createView(),
        ]);
    }
}
