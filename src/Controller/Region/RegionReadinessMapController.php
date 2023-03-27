<?php

namespace App\Controller\Region;

use App\Entity\ClusterZone;
use App\Entity\PhotosVersion;
use App\Entity\RepairPhotos;
use App\Form\editZoneRepairForm;
use App\Form\infrastructureSheetZoneRegionEditForm;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function viewZone(int $id, Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $today = new \DateTimeImmutable('now');
        $user = $this->getUser();
        $zone = $this->getDoctrine()->getManager()->getRepository(ClusterZone::class)->find($id);
        $repair = $zone->getZoneRepair();
        if($zone->getAddres()->getUser() !== $user)
        {
            return $this->redirectToRoute('app_region_readiness_map');
        }
        $query = $em->getRepository(RepairPhotos::class)
            ->createQueryBuilder('rp')
            ->leftJoin('rp.version', 'v')
            ->andWhere('v.repair = :repair')
            ->orderBy('v.id', 'DESC')
            ->setParameter('repair', $repair)
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('region_readiness_map/viewZone.html.twig', [
            'controller_name' => 'RegionReadinessMapController',
            'zone' => $zone,
            'pagination' => $pagination,
            'today' => $today
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

    /**
     * @Route("/readiness-map/infrastructure-sheet/{id}", name="app_region_rm_infrastructure_sheet")
     */
    public function editInfrastructureSheet(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);


        $form = $this->createForm(infrastructureSheetZoneRegionEditForm::class, $zone);
//        dd($request);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            foreach ($zone->getZoneInfrastructureSheets() as $sheet)
            {
                $entity_manager->persist($sheet);
            }
            $entity_manager->persist($zone);

            $entity_manager->flush();

            return $this->redirectToRoute('app_region_view_zone', ['id' => $id]);
        }

        return $this->render('region_readiness_map/editInfrastructureSheet.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' =>$form->createView(),
            'zone' => $zone,


        ]);
    }

    /**
     * @Route("/readiness-map/delete-photo-region/{zone_id}/{photo_id}", name="app_region_rm_delete_photo_region")
     */
    public function photoDelete(int $zone_id, int $photo_id, FileService $fileService)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $photo = $entity_manager->getRepository(RepairPhotos::class)->find($photo_id);
        $fileService->DeleteFile($photo->getPhoto(), 'repair_photos_directory');
        $entity_manager->remove($photo);
        $entity_manager->flush();

        return $this->redirectToRoute('app_region_view_zone', ['id' => $zone_id]);
    }

}
