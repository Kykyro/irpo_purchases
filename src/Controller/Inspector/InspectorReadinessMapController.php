<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterAddresses;
use App\Entity\ClusterZone;
use App\Entity\User;
use App\Form\addAddressesForm;
use App\Form\addZoneForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspector")
 */
class InspectorReadinessMapController extends AbstractController
{
    /**
     * @Route("/readiness-map/{id}", name="app_inspector_readiness_map")
     */
    public function index(int $id): Response
    {

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);

        return $this->render('inspector_readiness_map/index.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/readiness-map/add-addresses/{id}", name="app_inspector_add_addresses")
     */
    public function addAddresses(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $addres = new ClusterAddresses();
        $user = $entity_manager->getRepository(User::class)->find($id);
        $addres->setUser($user);

        $form = $this->createForm(addAddressesForm::class, $addres);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity_manager->persist($addres);
            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_readiness_map', ['id'=>$id]);
        }

        return $this->render('inspector_readiness_map/addAddresses.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/readiness-map/add-zone/{id}", name="app_inspector_add_zone")
     */
    public function addZone(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $zone = new ClusterZone();
        $addres = $entity_manager->getRepository(ClusterAddresses::class)->find($id);
        $zone->setAddres($addres);

        $form = $this->createForm(addZoneForm::class, $zone);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity_manager->persist($zone);
            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_readiness_map', ['id'=>$addres->getUser()->getId()]);
        }

        return $this->render('inspector_readiness_map/addZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/readiness-map/zone/{id}", name="app_inspector_view_zone")
     */
    public function viewZone(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);


        return $this->render('inspector_readiness_map/viewZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'zone' => $zone
        ]);
    }
}
