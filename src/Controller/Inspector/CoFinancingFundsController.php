<?php

namespace App\Controller\Inspector;

use App\Entity\CofinancingComment;
use App\Entity\CofinancingFunds;
use App\Entity\CofinancingScenario;
use App\Entity\User;

use App\Form\cofinancing\cofinancingCommentForm;
use App\Form\cofinancing\cofinancingFundsForm;
use App\Form\cofinancing\cofinancingScenarioEditForm;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspector")
 */
class CoFinancingFundsController extends AbstractController
{
    /**
     * @Route("/co-financing-funds/{id}", name="app_inspector_co_financing_funds")
     */
    public function index(EntityManagerInterface $em, Request $request, int $id, PaginatorInterface $paginator): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        $cofinancingComment = $user->getCofinancingComment();
        $funds = $user->getCofinancingFunds();
        $userInfo = $user->getUserInfo();
        if(is_null($cofinancingComment))
            $cofinancingComment = new CofinancingComment($user);
        if(is_null($funds))
            $funds = new CofinancingFunds($user);



        $form = $this->createForm(cofinancingCommentForm::class, $cofinancingComment);
        $formFunds = $this->createForm(cofinancingFundsForm::class, $funds);


        $form->handleRequest($request);
        $formFunds->handleRequest($request);

        if($formFunds->isSubmitted() and $formFunds->isValid())
        {

            $em->persist($funds);
            $em->flush();


            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }

        if($form->isSubmitted() and $form->isValid())
        {

            $em->persist($cofinancingComment);
            $em->flush();


            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }




        $query = $em->getRepository(CofinancingScenario::class)
            ->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->orderBy('c.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('co_financing_funds/inspector/index.html.twig', [
            'controller_name' => 'CoFinancingFundsController',
            'user' => $user,
            'cofinancing_scenarion' => $pagination,
            'form' => $form->createView(),
            'formFunds' => $formFunds->createView(),
            'cofinancingComment' => $cofinancingComment,
            'userInfo' => $userInfo,
            'funds' => $funds,
        ]);
    }

    /**
     * @Route("/co-financing-funds/edit/{id}", name="app_inspector_co_financing_funds_edit")
     */
    public function edit(EntityManagerInterface $em, Request $request, int $id)
    {
        $scenario = $em->getRepository(CofinancingScenario::class)->find($id);
        $user = $scenario->getUser();
        $formVars = [];

        $form = $this->createForm(cofinancingScenarioEditForm::class, $scenario);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($scenario);

            $em->flush();

            return $this->redirectToRoute('app_inspector_co_financing_funds', ['id' => $user->getId()]);
        }


        return $this->render('co_financing_funds/inspector/edit.html.twig', [
            'controller_name' => 'CoFinancingFundsController',
            'user' => $user,
            'form' => $form->createView(),

        ]);
    }
}
