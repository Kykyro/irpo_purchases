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
        $dumpDay = $dump->getCreatedAt();
        $pp = $serializer->deserialize($dumpData->getDump(), 'App\Entity\ProcurementProcedures[]' , 'json');




        return $this->render('weekly_budget_amount/viewDump.html.twig', [
            'controller_name' => 'WeeklyBudgetAmountController',
            'pp' => $pp,
            'initial' => $this->getInitialBudget($pp, $dumpDay),
            'fin' => $this->getFinBudget($pp, $dumpDay)
        ]);


    }
    public function getInitialBudget(array $dump, \DateTimeImmutable $day)
    {
        $sum = [
            'FederalFunds'  => 0,
            'FundsOfSubject' => 0,
            'EmployersFunds' => 0,
            'EducationalOrgFunds' => 0
        ];

        foreach ($dump as $item){
            if($item->getDateOfConclusion() >= $day)
            {
                $sum['FederalFunds'] += $item->getInitialFederalFunds();
                $sum['FundsOfSubject'] += $item->getInitialFundsOfSubject();
                $sum['EmployersFunds'] += $item->getInitialEmployersFunds();
                $sum['EducationalOrgFunds'] += $item->getInitialEducationalOrgFunds();
            }

        }

        return $sum;
    }

    public function getFinBudget(array $dump, \DateTimeImmutable $day)
    {

        $sum = [
            'FederalFunds'  => 0,
            'FundsOfSubject' => 0,
            'EmployersFunds' => 0,
            'EducationalOrgFunds' => 0
        ];

        foreach ($dump as $item){
            if($item->getDateOfConclusion() <= $day) {
                if($item->getMethodOfDetermining() == 'Единственный поставщик')
                {
                    $sum['FederalFunds'] += $item->getInitialFederalFunds();
                    $sum['FundsOfSubject'] += $item->getInitialFundsOfSubject();
                    $sum['EmployersFunds'] += $item->getInitialEmployersFunds();
                    $sum['EducationalOrgFunds'] += $item->getInitialEducationalOrgFunds();
                }
                else
                {
                    $sum['FederalFunds'] += $item->getFinFederalFunds();
                    $sum['FundsOfSubject'] += $item->getFinFundsOfSubject();
                    $sum['EmployersFunds'] += $item->getFinEmployersFunds();
                    $sum['EducationalOrgFunds'] += $item->getFinFundsOfEducationalOrg();
                }

            }
        }

        return $sum;
    }
}
