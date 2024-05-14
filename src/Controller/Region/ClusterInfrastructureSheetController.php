<?php

namespace App\Controller\Region;

use App\Entity\SheetWorkzone;
use App\Entity\WorkzoneEquipment;
use App\Entity\ZoneGroup;
use App\Form\InfrastructureSheets\infrastructureSheetForm;
use App\Form\InfrastructureSheets\SheetWorkzoneForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClusterInfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/region/infrastructure-sheet/edit/{id}", name="app_cluster_infrastructure_sheet_edit")
     * @throws \Exception
     */
    public function edit(Request $request, EntityManagerInterface $em, int $id)
    {
        $user = $this->getUser();
        $zoneGroup = $em->getRepository(ZoneGroup::class)->find($id);

        if($user->getId() != $zoneGroup->getSheetWorkzone()->getUser()->getId())
        {
            throw new \Exception("В доступе отказано", 403);
        }



        $form = $this->createForm(infrastructureSheetForm::class, $zoneGroup, ['zoneGroup' => [$zoneGroup]]);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
//            dd($data);

           $now = new \DateTimeImmutable('now');
            foreach($form->get('equipment') as $_equipment)
            {
                $equipment = $_equipment->getData();
                if(!$equipment->getZoneGroup())
                {
                    $equipment->setZoneGroup($zoneGroup);
                }
                if($_equipment->get('_comment'))
                {
                    $comment = $_equipment->get('_comment')->getData();
                    $commentHead = " \r\nКомментарий от кластера от ".$now->format('d.m.Y').": \r\n";
                    $equipment->setClusterComment($equipment->getClusterComment().$commentHead.$comment);
                }
                $em->persist($equipment);
            }
            $em->persist($zoneGroup);

            $em->flush();
        }

        return $this->render('cluster_infrastructure_sheet/edit.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
            'form' => $form->createView(),
            'zone_group' => $zoneGroup,
            'type' => $zoneGroup->getType(),
        ]);
    }


    public function editRequirements(Request $request, EntityManagerInterface $em, int $id): Response
    {
        return $this->render('cluster_infrastructure_sheet/edit_requirements.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
        ]);
    }

    /**
     * @Route("/region/infrastructure-sheet", name="app_cluster_infrastructure_sheet")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $zones = $em->getRepository(SheetWorkzone::class)->findBy([
           'user' =>  $user->getId()
        ]);

        return $this->render('cluster_infrastructure_sheet/index.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
            'zones' => $zones
        ]);
    }

    /**
     * @Route("/region/add-zone", name="app_cluster_infrastructure_sheet_add_zone")
     * @Route("/region/edit-zone/{id}", name="app_cluster_infrastructure_sheet_edit_zone")
     */
    public function editZone(Request $request, EntityManagerInterface $em, int $id = null): Response
    {

        if($id)
        {
            $zone = $em->getRepository(SheetWorkzone::class)->find($id);
        }
        else
        {
            $zone = new SheetWorkzone($this->getUser());
        }

        $form = $this->createForm(SheetWorkzoneForm::class, $zone);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            foreach ($zone->getZoneGroups() as $i)
            {
                if($i->getType())
                {
                    $i->setSheetWorkzone($zone);
                    $em->persist($i);
                }
                else
                {
                    $em->remove($i);
                }

            }


            $em->persist($zone);
            $em->flush();


            return $this->redirectToRoute('app_cluster_infrastructure_sheet');
        }

        return $this->render('cluster_infrastructure_sheet/edit_zone.html.twig', [
            'controller_name' => 'ClusterInfrastructureSheetController',
            'form' => $form->createView(),
        ]);
    }
}
