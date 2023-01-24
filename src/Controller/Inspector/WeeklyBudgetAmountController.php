<?php

namespace App\Controller\Inspector;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/inspector")
 */
class WeeklyBudgetAmountController extends AbstractController
{
    /**
     * @Route("/weekly-budget-amount/{id}", name="app_weekly_budget_amount")
     */
    public function index(int $id): Response
    {
        return $this->render('weekly_budget_amount/index.html.twig', [
            'controller_name' => 'WeeklyBudgetAmountController',
        ]);
    }
}
