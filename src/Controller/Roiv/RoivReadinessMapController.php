<?php

namespace App\Controller\Roiv;

use App\Entity\ClusterAddresses;
use App\Entity\ClusterZone;
use App\Entity\Log;
use App\Entity\PhotosVersion;
use App\Entity\RepairDump;
use App\Entity\RepairDumpGroup;
use App\Entity\RepairPhotos;
use App\Entity\User;
use App\Entity\ZoneInfrastructureSheet;
use App\Form\addAddressesForm;
use App\Form\addZoneForm;
use App\Form\infrastructureSheetZoneForm;
use App\Services\certificateReadinessMap;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use ZipArchive;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/roiv")
 */
class RoivReadinessMapController extends AbstractController
{
    /**
     * @Route("/readiness-map/{id}", name="app_roiv_readiness_map")
     */
    public function index(Request $request,int $id): Response
    {

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        $photos = null;
        $arr = [];


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

        ];

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

        $form->handleRequest($request);
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

        return $this->render('roiv_readiness_map/index.html.twig', [
            'user' => $user,
            '_photos' => $photos,
            'form' => $form->createView(),
            'mtb_fact' => ($count > 0) ? round((($proc['furniture']+$proc['PO']+$proc['equipment'])/$count)*100, 2): 0,
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
        $dir = '../public/uploads/repairPhotos/';

        $files = [];
        $filesNames = [];
        foreach ($photos as $version)
        {
            $addres = $version->getRepair()->getClusterZone()->getAddres()->getAddresses();
            foreach ($version->getRepairPhotos() as $i)
            {

                array_push($files, $dir . $i->getPhoto());
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


        return $this->file($temp_file, $fileName.'.zip', ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/readiness-map/zone/{id}", name="app_roiv_view_zone")
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

        return $this->render('roiv_readiness_map/viewZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'zone' => $zone,
            'pagination' => $pagination,
            'infrastructure' => $infrastructure,
        ]);
    }
    /**
     * @Route("/readiness-map/address/{id}", name="app_roiv_view_address")
     */
    public function viewAddres(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $address = $entity_manager->getRepository(ClusterAddresses::class)->find($id);


        return $this->render('roiv_readiness_map/viewAddress.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'address' => $address
        ]);
    }

    /**
     * @Route("/readiness-map/download/{id}", name="app_roiv_readiness_map_download")
     */
    public function downloadReadinessMap(int $id, certificateReadinessMap $readinessMap)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $entity_manager->getRepository(User::class)->find($id);

        return $readinessMap->getCertificate($user);
    }
//    /**
//     * @Route("/readiness-map/history/{id}", name="app_roiv_readiness_map_history")
//     */
//    public function readinessMapHistory(int $id, certificateReadinessMap $readinessMap)
//    {
//        $entity_manager = $this->getDoctrine()->getManager();
//        $user = $entity_manager->getRepository(User::class)->find($id);
//        $addresses = $user->getClusterAddresses();
//
//        $repairHistory = $entity_manager->getRepository(Log::class)
//            ->createQueryBuilder('l')
//            ->andWhere('i.object_class = :object_class')
//            ->setParameter('object_class', 'ZoneRepair')
//            ->orderBy('i.id', 'DESC')
//            ->getQuery()
//            ->getResult();
//
//        return $this->render('inspector_readiness_map/addAddresses.html.twig', [
//            'controller_name' => 'InspectorReadinessMapController',
//
//        ]);
//    }

//    /**
//     * @Route("/readiness-map/archive/{id}", name="app_roiv_readiness_map_archive")
//     */
//    public function readinessMapArchive(int $id)
//    {
//        $entity_manager = $this->getDoctrine()->getManager();
//        $user = $entity_manager->getRepository(User::class)->find($id);
//
//
//        return $this->render('inspector_readiness_map/readinessMapArcheve.html.twig', [
//            'controller_name' => 'InspectorReadinessMapController',
//            'user' => $user,
//
//        ]);
//    }



//    /**
//     * @Route("/readiness-map/repair/history/{id}", name="app_roiv_rm_repair_history")
//     */
//    public function repairHistory(int $id, Request $request, SerializerInterface $serializer)
//    {
//        $entity_manager = $this->getDoctrine()->getManager();
//        $user = $entity_manager->getRepository(User::class)->find($id);
//
//        $arr = [];
//        $form = $this->createFormBuilder($arr)
//            ->add('dump', EntityType::class, [
//                'attr' => ['class' => 'form-control select2'],
//                'required'   => false,
//                'class' => RepairDumpGroup::class,
//                'query_builder' => function (EntityRepository $er) use($user) {
//                    return $er->createQueryBuilder('g')
//                        ->leftJoin('g.repairDump', 'r')
//                        ->andWhere('r.user = :user')
//                        ->setParameter('user', $user)
//                        ->orderBy('g.createdAt', 'ASC')
//                        ;
//                },
//                'choice_label' => function($dump){
//                    return $dump->getCreatedAt()->format('d.m.Y');
//                },
//                'label' => 'Дата'
//            ])
//            ->add('submit', SubmitType::class, [
//                'attr' => [
//                    'class' => 'btn btn-success'
//                ],
//                'label' => 'Найти',
//            ])
//            ->getForm();
//
//        $repair_dump = null;
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $repair_dump = $form->getData();
//            $repair_dump = $repair_dump['dump'];
//        }
//        return $this->render('inspector_readiness_map/repairHistory.html.twig', [
//            'controller_name' => 'InspectorReadinessMapController',
//            'user' => $user,
//            'form' => $form->createView(),
//            'dump' => $repair_dump,
//            'serializer' => $serializer,
//
//        ]);
//    }

//    /**
//     * @Route("/readiness-map/actual-gallery/{id}", name="app_roiv_rm_actual_gallery_region")
//     */
//    public function actualGallery(int $id)
//    {
//        $entity_manager = $this->getDoctrine()->getManager();
//        $user = $entity_manager->getRepository(User::class)->find($id);
//
//        $arr = [];
//        $zones = $entity_manager->getRepository(ClusterZone::class)
//            ->createQueryBuilder('cz')
//            ->leftJoin('cz.addres', 'ca')
//            ->leftJoin('ca.user', 'u')
//            ->where('u.id = :userId')
//            ->setParameter('userId', $user->getId())
//            ->getQuery()
//            ->getResult()
//        ;
//        foreach ($zones as $zone)
//        {
//            $gallery = $entity_manager->getRepository(PhotosVersion::class)
//                ->createQueryBuilder('pv')
//                ->leftJoin('pv.repair', 'zr')
//                ->leftJoin('zr.clusterZone', 'cz')
//                ->where('cz.id = :zoneId')
//                ->setParameter('zoneId', $zone->getId())
//                ->orderBy('pv.id', 'DESC')
//                ->setMaxResults(1)
//                ->getQuery()
//                ->getResult()
//            ;
//            array_push($arr, $gallery);
//        }
//
//
//        return $this->render('inspector_readiness_map/actualGallery.html.twig', [
//            'controller_name' => 'InspectorReadinessMapController',
//            '_photos' => $arr,
//            'user' => $user
//        ]);
//    }
}
