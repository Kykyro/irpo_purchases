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
use Psr\Log\LoggerInterface;
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

        $adresses = $user->getClusterAddresses();
        $procentage = [
            'F' => 0,
            'G' => 0,
            'H' => 0,
            'I' => 0,
            'furniture' => 0,
            'furniture_fact' => 0,
            'PO' => 0,
            'PO_fact' => 0,
            'equipment' => 0,
            'equipment_fact' => 0,
            'furniture_put' => 0,
            'equipment_put' => 0,
            'PO_put' => 0,
        ];
        foreach ($adresses as $adress) {

            $zones = $adress->getClusterZones();

            foreach ($zones as $zone) {
                if($zone->getType()->getName() == "Зона по видам работ")
                {
                    $arr = $zone->getCountOfEquipmentByType();

                    $procentage['furniture'] += $arr['furniture'];
                    $procentage['furniture_fact'] += $arr['furniture_fact'];
                    $procentage['PO'] += $arr['PO'];
                    $procentage['PO_fact'] += $arr['PO_fact'];
                    $procentage['equipment'] += $arr['equipment'];
                    $procentage['equipment_fact'] += $arr['equipment_fact'];
                    $procentage['furniture_put'] += $arr['furniture_put'];
                    $procentage['equipment_put'] += $arr['equipment_put'];
                    $procentage['PO_put'] += $arr['PO_put'];
                }


            }

        }
        $count = 0;
        if($procentage['furniture'] > 0)
            $count++;
        if($procentage['PO'] > 0)
            $count++;
        if($procentage['equipment'] > 0)
            $count++;
        $proc = [
            'total' =>   $procentage['furniture']+$procentage['PO']+$procentage['equipment'],
            'furniture' => $this->midleProc($procentage['furniture'], $procentage['furniture_fact']),
            'PO' => $this->midleProc($procentage['PO'], $procentage['PO_fact']),
            'equipment' => $this->midleProc($procentage['equipment'], $procentage['equipment_fact']),
            'fact' => $procentage['furniture_fact']+$procentage['PO_fact']+$procentage['equipment_fact'],
            'put' => $procentage['furniture_put']+$procentage['PO_put']+$procentage['equipment_put'],
            'furniture_put' => $this->midleProc($procentage['furniture'], $procentage['furniture_put']),
            'PO_put' => $this->midleProc($procentage['PO'], $procentage['PO_put']),
            'equipment_put' => $this->midleProc($procentage['equipment'], $procentage['equipment_put']),


        ];






        return $this->render('region_readiness_map/index.html.twig', [
            'controller_name' => 'RegionReadinessMapController',
            'addresess' => $addresses,
            'user' => $user,
            'proc' => $proc,
            'mtb_fact' => ($count > 0) ? round((($proc['furniture']+$proc['PO']+$proc['equipment'])/$count)*100, 2): 0,
            'mtb_put' => ($count > 0) ? round((($proc['furniture_put']+$proc['PO_put']+$proc['equipment_put'])/$count)*100, 2): 0,

        ]);
    }

    public function midleProc($total, $fact)
    {
        if($total > 0){
            $result = $fact / $total;

            if($result > 0)
                return $result;
            else
                return 0;
        }
        else
        {
            return 0;
        }
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

                if(!str_contains($photo->getMimeType(), 'image/'))
                {
                    return $this->render('region_readiness_map/editRepairZone.html.twig', [
                        'controller_name' => 'RegionReadinessMapController',
                        'zone' => $zone,
                        'form' => $form->createView(),
                        'error' => 'Неверный формат файла'
                    ]);
                }

            }

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
    public function photoDelete(int $zone_id, int $photo_id, FileService $fileService, LoggerInterface $logger)
    {
//        $entity_manager = $this->getDoctrine()->getManager();
//        $photo = $entity_manager->getRepository(RepairPhotos::class)->find($photo_id);
//        $fileService->DeleteFile($photo->getPhoto(), 'repair_photos_directory');
//        $entity_manager->remove($photo);
//        $entity_manager->flush();
        $user = $this->getUser()->getId();
        $logger->info("Удаление фотографии userID: $user");
        return $this->redirectToRoute('app_region_view_zone', ['id' => $zone_id]);
    }

}
