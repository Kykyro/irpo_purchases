<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterAddresses;
use App\Entity\ClusterZone;
use App\Entity\Log;
use App\Entity\PhotosVersion;
use App\Entity\ReadinessMapCheckStatus;
use App\Entity\RepairDump;
use App\Entity\RepairDumpGroup;
use App\Entity\RepairPhotos;
use App\Entity\User;
use App\Entity\ZoneInfrastructureSheet;
use App\Entity\ZoneRemark;
use App\Form\addAddressesForm;
use App\Form\addZoneForm;
use App\Form\editZoneRepairForm;
use App\Form\infrastructureSheetZoneForm;
use App\Services\certificateReadinessMap;
use App\Services\excel\InfrastructureSheetFromReadinessMapXlsxService;
use App\Services\FileService;
use App\Services\monitoringReadinessMapService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use function PHPUnit\Framework\throwException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use ZipArchive;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/inspector")
 */
class InspectorReadinessMapController extends AbstractController
{
    /**
     * @Route("/readiness-map/{id}", name="app_inspector_readiness_map")
     */
    public function index(EntityManagerInterface $em, Request $request,int $id): Response
    {

        $user = $em->getRepository(User::class)->find($id);
        if(in_array("ROLE_BAS", $user->getRoles()))
        {
            $url = $this->generateUrl('app_readness_map_bas_inspector', $request->query->all() + ['id' => $user->getId()]);
            return $this->redirect($url);
        }
        $today = new \DateTimeImmutable('now');


        $photos = null;
        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('startDate', DateType::class, [
                'attr' => [
                    'class' => ''
                ],
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'attr' => [
                    'class' => ' '
                ],
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('addres', HiddenType::class, [])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Найти'
            ])
            ->add('download', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Скачать'
            ])
            ->getForm();

        $rmcs = new ReadinessMapCheckStatus();
        $formReadinessMapChecks = $this->createFormBuilder($rmcs)
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Комментарий',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
//                    'На рассмотрении' => 'На рассмотрении',
                    'На доработку' => 'На доработке',
                    'Принято' => 'Принято',
//                    'Исправлено' => 'Исправлено',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->getForm();

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
            'allowance' => 0,
            'allowance_fact' => 0,
            'allowance_put' => 0,
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
                    $procentage['allowance'] += $arr['allowance'];
                    $procentage['allowance_fact'] += $arr['allowance_fact'];
                    $procentage['allowance_put'] += $arr['allowance_put'];
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
        if($procentage['allowance'] > 0)
            $count++;
        $proc = [
            'total' =>   $procentage['furniture']+$procentage['PO']+$procentage['equipment']+$procentage['allowance'],
            'furniture' => $this->midleProc($procentage['furniture'], $procentage['furniture_fact']),
            'PO' => $this->midleProc($procentage['PO'], $procentage['PO_fact']),
            'allowance' => $this->midleProc($procentage['allowance'], $procentage['allowance_fact']),
            'equipment' => $this->midleProc($procentage['equipment'], $procentage['equipment_fact']),
            'furniture_put' => $this->midleProc($procentage['furniture'], $procentage['furniture_put']),
            'PO_put' => $this->midleProc($procentage['PO'], $procentage['PO_put']),
            'equipment_put' => $this->midleProc($procentage['equipment'], $procentage['equipment_put']),
            'allowance_put' => $this->midleProc($procentage['allowance'], $procentage['allowance_put']),
            'fact' => $procentage['furniture_fact']+$procentage['PO_fact']+$procentage['equipment_fact']+$procentage['allowance_fact'],
            'put' => $procentage['furniture_put']+$procentage['PO_put']+$procentage['equipment_put']+$procentage['allowance_put'],

        ];




        $form->handleRequest($request);
        $formReadinessMapChecks->handleRequest($request);

        if ($formReadinessMapChecks->isSubmitted() && $formReadinessMapChecks->isValid())
        {
            $readinessMapCheck = $user->getReadinessMapChecks()->last();
            if($readinessMapCheck){
                $readinessMapCheck->addStatus($rmcs);

                $em->persist($rmcs);
                $em->flush();
            }

            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $entity_manager = $this->getDoctrine()->getManager();
            $photos = $entity_manager->getRepository(PhotosVersion::class)
                ->createQueryBuilder('pv')
                ->leftJoin('pv.repair', 'zr')
                ->leftJoin('zr.clusterZone', 'cz')
                ->leftJoin('cz.addres', 'ca')
                ->leftJoin('ca.user', 'u')
                ->where('u.id = :userId')
                ->andWhere('pv.createdAt >= :startDate and pv.createdAt <= :endDate')
                ->setParameter('startDate', $data['startDate'])
                ->setParameter('endDate', $data['endDate']->setTime(23,59,59,0))
                ->setParameter('userId', $user->getId())
                ->orderBy('cz.name', 'ASC')
                ->getQuery()
                ->getResult()
                ;

            if($form->get('download')->isClicked() and count($photos) > 0)
                return $this->downloadPhotos($photos, $user->getUserInfo()->getCluster());
        }

        if($user->getUserInfo()->getYear() == 2023)
        {
            $mtb_fact = ($count > 0) ? round((($proc['furniture']+$proc['PO']+$proc['equipment']+$proc['allowance'])/$count)*100, 2): 0;
            $mtb_put = ($count > 0) ? round((($proc['furniture_put']+$proc['PO_put']+$proc['equipment_put']+$proc['allowance_put'])/$count)*100, 2): 0;
        }
        else
        {
            $mtb_fact = ($count > 0) ? round((($proc['fact'])/$proc['total'])*100, 2): 0;
            $mtb_put = ($count > 0) ? round((($proc['put'])/$proc['total'])*100, 2): 0;
        }


        return $this->render('inspector_readiness_map/index.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'user' => $user,
            '_photos' => $photos,
            'form' => $form->createView(),
            'form_checks' => $formReadinessMapChecks->createView(),
            'proc' => $proc,
            'mtb_fact' => $mtb_fact,
            'mtb_put' => $mtb_put,
            'today' => $today
//            'mtb_put' => ($count > 0) ? round((($proc['furniture_put']+$proc['PO_put']+$proc['equipment_put']+$proc['allowance_put'])/$count)*100, 2): 0,
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

    public function downloadPhotos($photos, $fileName = "file")
    {
        $dir = $this->getParameter('repair_photos_directory');
        $fileName = "file.zip";
        $files = [];
        $filesNames = [];
        foreach ($photos as $version)
        {
            $addres = $version->getRepair()->getClusterZone()->getAddres()->getAddresses();
            foreach ($version->getRepairPhotos() as $i)
            {

                array_push($files, $dir ."/". $i->getPhoto());
                $photoDir = $addres;
                $path_parts = pathinfo($i->getPhoto());
                array_push($filesNames,  $photoDir.'/'.$version->getRepair()->getClusterZone()->getName()
                    .'_'.uniqid().'.'.$path_parts['extension']);

            }
        }
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $zip = new ZipArchive();

        $zip->open($temp_file, \ZipArchive::CREATE);

        for ($i = 0; $i < count($files); $i++)
        {
            $zip->addFile($files[$i], $filesNames[$i]);

        }
        $zip->close();

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/readiness-map/download-infratructure-sheet/{id}", name="app_inspector_download_infrastructure_sheet")
     */
    public function downloadInfrastructureSheet(int $id, InfrastructureSheetFromReadinessMapXlsxService $service)
    {
        return $service->generate($id);
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

        $form = $this->createForm(addZoneForm::class, $zone, [
            'attr' => ['user' => $zone->getAddres()->getUser()->getId()]
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            if($zone->getType()->getName() == "Иное")
//            {
//                $zone->getZoneRepair()->setBranding(100);
//            }
            $entity_manager->persist($zone);
            $entity_manager->flush();

            $url = $this->generateUrl('app_inspector_readiness_map', $request->query->all() + ['id' => $addres->getUser()->getId(), 'tab'=>$addres->getId()]);
//            dd($url);
            return $this->redirect($url);
        }

        return $this->render('inspector_readiness_map/addZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/readiness-map/zone/{id}", name="app_inspector_view_zone")
     */
    public function viewZone(Request $request, int $id, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);
        $repair = $zone->getZoneRepair();
        $query = $em->getRepository(PhotosVersion::class)
            ->createQueryBuilder('p')
            ->andWhere('p.repair = :repair')
            ->orderBy('p.id', 'DESC')
            ->setParameter('repair', $repair)
            ->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        $infrastructure = $entity_manager->getRepository(ZoneInfrastructureSheet::class)
            ->createQueryBuilder('i')
            ->andWhere('i.zone = :zone')
            ->setParameter('zone', $zone)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('inspector_readiness_map/viewZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'zone' => $zone,
            'pagination' => $pagination,
            'infrastructure' => $infrastructure,
        ]);
    }
    /**
     * @Route("/readiness-map/address/{id}", name="app_inspector_view_address")
     */
    public function viewAddres(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $address = $entity_manager->getRepository(ClusterAddresses::class)->find($id);


        return $this->render('inspector_readiness_map/viewAddress.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'address' => $address
        ]);
    }

    /**
     * @Route("/readiness-map/add-remark/{id}", name="app_inspector_add_zone_remark")
     */
    public function addZoneRemark(Request $request, int $id, EntityManagerInterface $em)
    {
        $zone = $em->getRepository(ClusterZone::class)->find($id);
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('zoneRemark', $submittedToken)) {
            $remark = new ZoneRemark();
            $remark->setDescription($request->request->get('description'));
            $remark->setZone($zone);

            $em->persist($remark);
            $em->flush();
        }


        return $this->redirectToRoute('app_inspector_view_zone', ['id' => $id]);
    }

    /**
     * @Route("/readiness-map/delete-remark/{id}", name="app_inspector_delete_zone_remark")
     */
    public function deleteRemark(int $id, EntityManagerInterface $em)
    {
        $remark = $em->getRepository(ZoneRemark::class)->find($id);
        $zone = $remark->getZone();

        $em->remove($remark);
        $em->flush();

        return $this->redirectToRoute('app_inspector_view_zone', ['id' => $zone->getId()]);
    }

    /**
     * @Route("/readiness-map/infrastructure-sheet/{id}", name="app_inspector_rm_infrastructure_sheet")
     */
    public function editInfrastructureSheet(Request $request, int $id, EntityManagerInterface $em)
    {

        $zone = $em->getRepository(ClusterZone::class)->find($id);

        $form = $this->createForm(infrastructureSheetZoneForm::class, $zone);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {

            foreach ($zone->getZoneInfrastructureSheets() as $sheet)
            {

                if($sheet->getName() == "")
                {
                    if(!is_null($sheet->getId()))
                    {
                        $em->remove($sheet);
                    }
                }
                else
                {
                    $sheet->setZone($zone);
                    $em->persist($sheet);
                }
            }

            $em->persist($zone);

            $em->flush();

            return $this->redirectToRoute('app_inspector_view_zone', ['id' => $id]);
        }

        return $this->render('inspector_readiness_map/editInfrastructureSheet.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' =>$form->createView(),
            'id' => $id,

        ]);
    }

    /**
     * @Route("/readiness-map/delete-zone-inspector/{zone_id}", name="app_inspector_rm_delete_zone")
     */
    public function deleteZone(int $zone_id, FileService $fileService)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $zone = $entity_manager->getRepository(ClusterZone::class)->find($zone_id);
        $addres = $zone->getAddres();
        $repair = $zone->getZoneRepair();
        $zone->setZoneRepair(null);
        $photoVersion = $repair->getPhotosVersions();
        $infrastructureSheet = $zone->getZoneInfrastructureSheets();

        foreach ($photoVersion as $i){
            $photos = $i->getRepairPhotos();
            $i->setRepair(null);
            $repair->removePhotosVersion($i);
            foreach ($photos as $photo)
            {
                $fileService->DeleteFile($photo->getPhoto(), 'repair_photos_directory');
                $entity_manager->remove($photo);
            }
            $entity_manager->remove($i);
        }

        foreach ($infrastructureSheet as $i)
        {
            $entity_manager->remove($i);
        }

        $entity_manager->remove($repair);
        $entity_manager->remove($zone);
        $entity_manager->flush();

        return $this->redirectToRoute('app_inspector_view_address', ['id' => $addres->getId()]);
    }

    /**
     * @Route("/readiness-map/edit-zone/{id}", name="app_inspector_edit_zone")
     */
    public function editZone(Request $request, int $id, FileService $fileService)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);

        $form = $this->createForm(addZoneForm::class, $zone, [
            'attr' => ['user' => $zone->getAddres()->getUser()->getId()]
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $designProject = $form->get('designProject')->getData();
            if($designProject)
            {
                $zone->setDesignProjectFile($fileService->UploadFile($designProject, 'zones_design_project_directory'));
            }



            $entity_manager->persist($zone);
            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_view_address', ['id'=>$zone->getAddres()->getId()]);
        }

        return $this->render('inspector_readiness_map/addZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/readiness-map/edit-addresses/{id}", name="app_inspector_edit_addresses")
     */
    public function editAddresses(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $addres = $entity_manager->getRepository(ClusterAddresses::class)->find($id);
//        $user = $entity_manager->getRepository(User::class)->find($id);
//        $addres->setUser($user);

        $form = $this->createForm(addAddressesForm::class, $addres);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity_manager->persist($addres);
            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_readiness_map', ['id'=>$addres->getUser()->getId()]);
        }

        return $this->render('inspector_readiness_map/addAddresses.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/readiness-map/download/{id}", name="app_inspector_readiness_map_download")
     */
    public function downloadReadinessMap(int $id, certificateReadinessMap $readinessMap)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);

        return $readinessMap->getCertificate($user);
    }
    /**
     * @Route("/readiness-map/history/{id}", name="app_inspector_readiness_map_history")
     */
    public function readinessMapHistory(int $id, certificateReadinessMap $readinessMap)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);
        $addresses = $user->getClusterAddresses();

        $repairHistory = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('i.object_class = :object_class')
            ->setParameter('object_class', 'ZoneRepair')
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('inspector_readiness_map/addAddresses.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',

        ]);
    }

    /**
     * @Route("/readiness-map/archive/{id}", name="app_inspector_readiness_map_archive")
     */
    public function readinessMapArchive(int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);


        return $this->render('inspector_readiness_map/readinessMapArcheve.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'user' => $user,

        ]);
    }

    /**
     * @Route("/readiness-map/delete-photo-region/{zone_id}/{photo_id}", name="app_inspector_rm_delete_photo_region")
     */
    public function photoDelete(int $zone_id, int $photo_id, FileService $fileService)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $photo = $entity_manager->getRepository(RepairPhotos::class)->find($photo_id);
        $fileService->DeleteFile($photo->getPhoto(), 'repair_photos_directory');
        $entity_manager->remove($photo);
        $entity_manager->flush();

        return $this->redirectToRoute('app_inspector_view_zone', ['id' => $zone_id]);
    }

    /**
     * @Route("/readiness-map/repair/history/{id}", name="app_inspector_rm_repair_history")
     */
    public function repairHistory(int $id, Request $request, SerializerInterface $serializer)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('dump', EntityType::class, [
                'attr' => ['class' => 'form-control select2'],
                'required'   => false,
                'class' => RepairDumpGroup::class,
                'query_builder' => function (EntityRepository $er) use($user) {
                    return $er->createQueryBuilder('g')
                        ->leftJoin('g.repairDump', 'r')
                        ->andWhere('r.user = :user')
                        ->setParameter('user', $user)
                        ->orderBy('g.createdAt', 'ASC')
                        ;
                },
                'choice_label' => function($dump){
                    return $dump->getCreatedAt()->format('d.m.Y');
                },
                'label' => 'Дата'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Найти',
            ])
            ->getForm();

        $repair_dump = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repair_dump = $form->getData();
            $repair_dump = $repair_dump['dump'];
        }
        return $this->render('inspector_readiness_map/repairHistory.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'user' => $user,
            'form' => $form->createView(),
            'dump' => $repair_dump,
            'serializer' => $serializer,

        ]);
    }

    /**
     * @Route("/readiness-map/actual-gallery/{id}", name="app_inspector_rm_actual_gallery_region")
     */
    public function actualGallery(int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);
//        $gallery = $photos = $entity_manager->getRepository(PhotosVersion::class)
//            ->createQueryBuilder('pv')
//            ->leftJoin('pv.repair', 'zr')
//            ->leftJoin('zr.clusterZone', 'cz')
//            ->leftJoin('cz.addres', 'ca')
//            ->leftJoin('ca.user', 'u')
//            ->where('u.id = :userId')
//            ->setParameter('userId', $user->getId())
//            ->orderBy('pv.createdAt', 'ASC')
//            ->setMaxResults(1)
//            ->getQuery()
//            ->getResult()
//        ;
        $arr = [];
        $zones = $entity_manager->getRepository(ClusterZone::class)
            ->createQueryBuilder('cz')
            ->leftJoin('cz.addres', 'ca')
            ->leftJoin('ca.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult()
        ;
        foreach ($zones as $zone)
        {
            $gallery = $entity_manager->getRepository(PhotosVersion::class)
                ->createQueryBuilder('pv')
                ->leftJoin('pv.repair', 'zr')
                ->leftJoin('zr.clusterZone', 'cz')
                ->where('cz.id = :zoneId')
                ->setParameter('zoneId', $zone->getId())
                ->orderBy('pv.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult()
            ;
            array_push($arr, $gallery);
        }


        return $this->render('inspector_readiness_map/actualGallery.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            '_photos' => $arr,
            'user' => $user
        ]);
    }

    /**
     * @Route("/readiness-map/edit-repair-zone/{id}", name="app_inspector_edit_repair_zone")
     */
    public function editRepairZone(Request $request, int $id, FileService $fileService): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);


        $repair = $zone->getZoneRepair();
        $form = $this->createForm(editZoneRepairForm::class, $repair);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity_manager->persist($repair);
            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_view_zone', ['id'=>$zone->getId()]);
        }

        return $this->render('inspector_readiness_map/editRepairZone.html.twig', [
            'zone' => $zone,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/readiness-map/monitoring/{id}", name="app_inspector_readiness_map_monitoring")
     */
    public function getMonitoring(int $id, EntityManagerInterface $em, monitoringReadinessMapService $service)
    {
        throw new Exception(403);
        $user = $em->getRepository(User::class )->find($id);
        return $service->getCertificate($user);
        return $this->redirectToRoute('app_inspector_readiness_map', ['id'=> $id]);
    }
}
