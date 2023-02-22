<?php

namespace App\Controller\Region;

use App\Entity\ClusterZone;
use App\Entity\PhotosVersion;
use App\Entity\RepairPhotos;
use App\Form\editZoneRepairForm;
use App\Services\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/region")
 */
class RegionReadinessMapController extends AbstractController
{
    /**
     * @Route("/readiness-map", name="app_region_readiness_map")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $addresses = $user->getClusterAddresses();



        return $this->render('region_readiness_map/index.html.twig', [
            'controller_name' => 'RegionReadinessMapController',
            'addresess' => $addresses,
        ]);
    }

    /**
     * @Route("/readiness-map/view-zone/{id}", name="app_region_view_zone")
     */
    public function viewZone(int $id): Response
    {
        $user = $this->getUser();
        $zone = $this->getDoctrine()->getManager()->getRepository(ClusterZone::class)->find($id);
        if($zone->getAddres()->getUser() !== $user)
        {
            return $this->redirectToRoute('app_region_readiness_map');
        }

        return $this->render('region_readiness_map/viewZone.html.twig', [
            'controller_name' => 'RegionReadinessMapController',
            'zone' => $zone,
        ]);
    }

    /**
     * @Route("/readiness-map/edit-repair-zone/{id}", name="app_region_edit_repair_zone")
     */
    public function editZone(Request $request, int $id, FileService $fileService): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);
        if($zone->getAddres()->getUser() !== $user)
        {
            return $this->redirectToRoute('app_region_readiness_map');
        }

        $repair = $zone->getZoneRepair();
        $form = $this->createForm(editZoneRepairForm::class, $repair);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photosFromForm = $form->get('images')->getData();
            $version = new PhotosVersion();

            foreach ($photosFromForm as $photo)
            {
                $filename = $fileService->UploadFile($photo, 'repair_photos_directory');
                $_photo = new RepairPhotos();
                $_photo->setPhoto($filename);
                $version->addRepairPhoto($_photo);
                $entity_manager->persist($_photo);
            }
            $version->setRepair($repair);

            $entity_manager->persist($version);
            $entity_manager->persist($repair);
            $entity_manager->flush();

            return $this->redirectToRoute('app_region_view_zone', ['id'=>$zone->getId()]);
        }

        return $this->render('region_readiness_map/editRepairZone.html.twig', [
            'controller_name' => 'RegionReadinessMapController',
            'zone' => $zone,
            'form' => $form->createView()
        ]);
    }


}
