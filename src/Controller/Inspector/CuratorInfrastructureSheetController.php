<?php

namespace App\Controller\Inspector;

use App\Entity\SheetWorkzone;
use App\Entity\User;
use App\Entity\WorkzoneEquipment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @Route("/inspector/equipment/save/{id}", name="app_curator_save_equipment")
     */
    public function saveEquipment(EntityManagerInterface $em, int $id, Request $request)
    {

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('infrastructure-equipment', $submittedToken)) {
            $equipment = $em->getRepository(WorkzoneEquipment::class)->find($id);

//            $equipment->setDone($request->request->get('is-done'));
            $equipment->setDeleted($request->request->get('allow-deleted'));
            $equipment->setCuratorComment($request->request->get('comment'));
            $em->persist($equipment);
            $em->flush();

        }

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

}
