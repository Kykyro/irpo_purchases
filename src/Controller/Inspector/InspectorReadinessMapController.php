<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterAddresses;
use App\Entity\ClusterZone;
use App\Entity\PhotosVersion;
use App\Entity\User;
use App\Entity\ZoneInfrastructureSheet;
use App\Form\addAddressesForm;
use App\Form\addZoneForm;
use App\Form\infrastructureSheetZoneForm;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function index(Request $request,int $id): Response
    {

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        $photos = null;
        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('startDate', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
            ])
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
                ->andWhere('u.id = :userId')
                ->andWhere('pv.createdAt >= :startDate')
                ->andWhere('pv.createdAt <= :endDate')
                ->setParameter('startDate', $data['startDate'])
                ->setParameter('endDate', $data['endDate'])
                ->setParameter('userId', $user->getId())
                ->orderBy('cz.name', 'ASC')
                ->getQuery()
                ->getResult()
                ;

            if($form->get('download')->isClicked() and count($photos) > 0)
                return $this->downloadPhotos($photos, $user->getUserInfo()->getCluster());
        }

        return $this->render('inspector_readiness_map/index.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'user' => $user,
            '_photos' => $photos,
            'form' => $form->createView(),
        ]);
    }

    public function downloadPhotos($photos, $fileName = "file")
    {
//        $this->getParameter($directory);

        $dir = '../public/uploads/repairPhotos/';

        $files = [];
        $filesNames = [];
        foreach ($photos as $version)
        {
            foreach ($version->getRepairPhotos() as $i)
            {
                array_push($files, $dir . $i->getPhoto());
                $photoDir = $version->getRepair()->getClusterZone()->getName();
                array_push($filesNames,  $photoDir.'/'.$i->getPhoto());
            }
        }
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $zip = new ZipArchive();

        $zip->open($temp_file, \ZipArchive::CREATE);

        for ($i = 0; $i < count($files); $i++)
        {
            $zip->addFile($files[$i], $filesNames[$i]);
        }


        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

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
     * @Route("/readiness-map/infrastructure-sheet/{id}", name="app_inspector_rm_infrastructure_sheet")
     */
    public function editInfrastructureSheet(Request $request, int $id)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $zone = $entity_manager->getRepository(ClusterZone::class)->find($id);


        $form = $this->createForm(infrastructureSheetZoneForm::class, $zone);
//        dd($request);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            foreach ($zone->getZoneInfrastructureSheets() as $sheet)
            {
                if($sheet->getName() == "")
                {
                    if(!is_null($sheet->getId()))
                        $entity_manager->remove($sheet);

                }
                else
                {
                    $sheet->setZone($zone);
                    $entity_manager->persist($sheet);
                }

            }
            $entity_manager->persist($zone);

            $entity_manager->flush();

            return $this->redirectToRoute('app_inspector_view_zone', ['id' => $id]);
        }

        return $this->render('inspector_readiness_map/editInfrastructureSheet.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' =>$form->createView(),


        ]);
    }

}
