<?php

namespace App\Controller\BasCurator;

use App\Entity\ClusterZone;
use App\Entity\PhotosVersion;
use App\Entity\UAVsCertificate;
use App\Entity\User;
use App\Entity\ZoneInfrastructureSheet;
use App\Form\BASequipment\equipmentBasInspectorForm;
use App\Form\editZoneRepairForm;
use App\Form\infrastructureSheetZoneForm;
use App\Services\BAS\AddTypicalBasService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadnessMapBasController extends AbstractController
{
    /**
     * @Route("/bas-curator/readness-map-bas/{id}", name="app_readness_map_bas_inspector")
     */
    public function index(EntityManagerInterface $em, Request $request,int $id): Response
    {

        $user = $em->getRepository(User::class)->find($id);

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

//            if($form->get('download')->isClicked() and count($photos) > 0)
//                return $this->downloadPhotos($photos, $user->getUserInfo()->getCluster());
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
        return $this->render('readness_map_bas/inspector/index.html.twig', [
            'controller_name' => 'ReadnessMapBasController',
            'user' => $user,
            '_photos' => $photos,
            'form' => $form->createView(),
            'proc' => $proc,
            'mtb_fact' => $mtb_fact,
            'mtb_put' => $mtb_put,

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
     * @Route("/bas-curator/readiness-map/zone/{id}", name="app_inspector_view_zone_bas")
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

        return $this->render('/readness_map_bas/inspector/viewZone.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'zone' => $zone,
            'pagination' => $pagination,
            'infrastructure' => $infrastructure,
        ]);
    }

    /**
     * @Route("/bas-curator/readiness-map/edit-repair-zone/{id}", name="app_inspector_edit_repair_zone_bas")
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
     * @Route("/bas-curator/readiness-map/approve-certificate/{id}", name="app_certificate_bas_approve")
     */
    public function approveCertificate(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('token');
//        dd($submittedToken);
        if ($this->isCsrfTokenValid('approve-certification', $submittedToken)) {

            $user = $em->getRepository(User::class)->find($id);
            $certificate = $user->getUAVsCertificate();
            if(is_null($certificate))
            {
                $user->setUAVsCertificate(new UAVsCertificate());
                $certificate = $user->getUAVsCertificate();
            }

            $status = $request->request->get('optionsRadios');
            $certificate->setStatus($status);
            $em->persist($user);
            $em->persist($certificate);
            $em->flush();
        }

        return $this->redirectToRoute('app_readness_map_bas_inspector', ['id' => $id]);
    }


    /**
     * @Route("/bas-curator/readiness-map/infrastructure-sheet/{id}", name="app_inspector_rm_infrastructure_sheet_bas")
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

            return $this->redirectToRoute('app_inspector_view_zone_bas', ['id' => $id]);
        }

        return $this->render('bas_curator/editInfrastructureSheet.html.twig', [
            'controller_name' => 'InspectorReadinessMapController',
            'form' =>$form->createView(),
            'id' => $id,

        ]);
    }

    /**
     * @Route("/bas-curator/readiness-map/infrastructure-sheet-add-typical/{id}", name="app_inspector_rm_infrastructure_sheet_bas_add_typical")
     */
    public function addTypicalIL(Request $request, int $id, EntityManagerInterface $em, AddTypicalBasService $service)
    {
        $service->add($id, $em);
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @Route("/bas-curator/equipment/{id}", name="app_inspector_bas_equipment_edit")
     */
    public function equipmentEdit(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(equipmentBasInspectorForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($user);
            $em->flush();
        }

        return $this->render('bas_curator/equipment.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
