<?php

namespace App\Controller;

use App\Entity\UserInfo;
use App\Form\perfomanceIndicatorEditForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspector")
 */
class PerfomanceIndicatorsEditController extends AbstractController
{
    /**
     * @Route("/perfomance-indicators-edit/{id}", name="app_perfomance_indicators_edit")
     */
    public function index($id, EntityManagerInterface $em, Request $request ): Response
    {
        $user_info = $em->getRepository(UserInfo::class)
            ->find($id);

        $form = $this->createForm(perfomanceIndicatorEditForm::class, $user_info);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            foreach ($user_info->getClusterPerfomanceIndicators() as $pi)
            {
                $pi->setUserInfo($user_info);
                $em->persist($pi);
            }
//            dd($user_info->getClusterPerfomanceIndicators());
            $em->persist($user_info);


            $em->flush();
//            dd($form->getData());
            return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id'=> $user_info->getId()]);
        }


        return $this->render('perfomance_indicators_edit/index.html.twig', [
            'controller_name' => 'PerfomanceIndicatorsEditController',
            'form' => $form->createView()
        ]);
    }
}
