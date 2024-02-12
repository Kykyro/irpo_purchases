<?php

namespace App\Controller\Inspector;

use App\Entity\SheetWorkzone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuratorInfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/inspector/infrastructure-sheet/{id}", name="app_curator_infrastructure_sheet")
     */
    public function index(EntityManagerInterface $em, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        $workZones = $user->getSheetWorkzones();

        return $this->render('curator_infrastructure_sheet/index.html.twig', [
            'controller_name' => 'CuratorInfrastructureSheetController',
            'workzones' => $workZones,
            'user' => $user
        ]);
    }

    /**
     * @Route("/inspector/infrastructure-sheet/view/{id}", name="app_curator_infrastructure_sheet_view")
     */
    public function zoneView(EntityManagerInterface $em, int $id)
    {
        $zone = $em->getRepository(SheetWorkzone::class)->find($id);

        return $this->render('curator_infrastructure_sheet/zone_view.html.twig', [
            'controller_name' => 'CuratorInfrastructureSheetController',
            'zone' => $zone
        ]);
    }

}
