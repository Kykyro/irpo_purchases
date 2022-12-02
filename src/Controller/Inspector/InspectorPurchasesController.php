<?php

namespace App\Controller\Inspector;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\InspectorPurchasesFindFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorPurchasesController extends AbstractController
{
    /**
     * @Route("/purchases", name="app_inspector_purchases", methods="GET|POST")
     */
    public function index(Request $request): Response
    {


        $form = $this->createForm(InspectorPurchasesFindFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('app_inspector_finded_cluster',
                [
                    'region' => $data['rf_subject']->getid(),
                    'year' => $data['year'],
                ]);
        }

        return $this->render('inspector/templates/findCluster.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/find-clusters/{year}/{region}", name="app_inspector_finded_cluster", methods="GET|POST")
     */
    public function selectCluster(Request $request, int $year, int $region, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $role = 'ROLE_REGION';

        $query = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->leftJoin('uf.rf_subject', 'rfs')
            ->andWhere('a.roles LIKE :role')
            ->andWhere('uf.year = :year')
            ->andWhere('rfs.id = :region')
            ->setParameter('role', "%$role%")
            ->setParameter('year', "$year")
            ->setParameter('region', "$region");

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('inspector/templates/selectCluster.html.twig', [
            'controller_name' => 'InspectorController',
            'pagination' => $pagination,

        ]);
    }
    /**
     * @Route("/show-purchases/{id}", name="app_inspector_show_purchases", methods="GET|POST")
     */
    public function showPurchases(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $prodProc = $entity_manager->getRepository(ProcurementProcedures::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', "$id")
            ->getQuery()
            ->getResult();


        return $this->render('inspector/templates/showPurchases.html.twig', [
            'controller_name' => 'InspectorController',
            'prodProc' => $prodProc,
            'id' => $id,

        ]);
    }

    /**
     * @Route("/get/purchasses-xlsx/{user_id}", name="download_purchases_xlsx")
     */
    public function download(XlsxService $xlsxService, int $user_id): Response
    {
        return $xlsxService->generatePurchasesProcedureTable($user_id);
    }

    /**
     * @Route("/get/purchasses-xlsx-all-by-year/{year}", name="download_purchases_all_by_year_xlsx")
     */
    public function downloadAllByYear(XlsxService $xlsxService, int $year): Response
    {
        return $xlsxService->generateAllPurchasesProcedureTable($year);
    }
    /**
     * @Route("/get/ready-map-xlsx/{year}", name="download_ready_map_xlsx")
     */
    public function downloadReadyMaps(XlsxService $xlsxService, int $year): Response
    {
//        return $xlsxService->generateAllPurchasesProcedureTable($year);
    }

}
