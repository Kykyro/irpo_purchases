<?php

namespace App\Controller\Inspector;

use App\Entity\PurchasesDump;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
        $entity_manager = $this->getDoctrine()->getManager();

        $user = $entity_manager->getRepository(User::class)->find($id);

        $purchasesDump = $user->getPurchasesDumps();


        return $this->render('weekly_budget_amount/index.html.twig', [
            'controller_name' => 'WeeklyBudgetAmountController',
            'dump' => $purchasesDump

        ]);
    }

    /**
     * @Route("/view-purchases-dump/{id}", name="app_view_dump")
     */
    public function viewDump(int $id, SerializerInterface $serializer)
    {

        $entity_manager = $this->getDoctrine()->getManager();
        $dump = $entity_manager->getRepository(PurchasesDump::class)->find($id);

        $dumpData = $dump->getDump();

        $pp = $serializer->deserialize($dumpData->getDump(), 'App\Entity\ProcurementProcedures[]' , 'json');

        return $this->render('weekly_budget_amount/viewDump.html.twig', [
            'controller_name' => 'WeeklyBudgetAmountController',
            'pp' => $pp,

        ]);


    }

}
