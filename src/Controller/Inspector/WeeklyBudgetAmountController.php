<?php

namespace App\Controller\Inspector;

use App\Entity\PurchasesDump;
use App\Entity\User;
use App\Services\budgetSumService;
use App\Services\XlsxService;
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
    public function viewDump(int $id, SerializerInterface $serializer, budgetSumService $budgetSumService)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $dump = $entity_manager->getRepository(PurchasesDump::class)->find($id);

        $dumpData = $dump->getDump();
        $dumpDay = $dump->getCreatedAt();

        $pp = $serializer->deserialize($dumpData->getDump(), 'App\Entity\ProcurementProcedures[]' , 'json');
        $arr = [];
        foreach ($pp as $p)
        {
            if(!$p->getIsDeleted())
            {
                array_push($arr, $p);
            }
        }
        return $this->render('weekly_budget_amount/viewDump.html.twig', [
            'controller_name' => 'WeeklyBudgetAmountController',
            'pp' => $arr,
            'initial' => $budgetSumService->getInitialBudget($arr, $dumpDay),
            'fin' => $budgetSumService->getFinBudget($arr, $dumpDay),
            'id' => $id
        ]);


    }

    /**
     * @Route("/get/xlsx_by_dump/{dump_id}", name="download_xlsx")
     */
    public function download(XlsxService $xlsxService, int $dump_id, SerializerInterface $serializer): Response
    {
        return $xlsxService->generatePurchasesProcedureByDump($dump_id, $serializer);
    }
}
