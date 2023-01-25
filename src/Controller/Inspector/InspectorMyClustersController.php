<?php

namespace App\Controller\Inspector;

use App\Entity\FavoritesClusters;
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
class InspectorMyClustersController extends AbstractController
{
    /**
     * @Route("/my-clusters", name="app_inspector_my_clusters")
     */
    public function index(Request $request): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cluster = $entity_manager->getRepository(FavoritesClusters::class)->findBy(
            ['inspectorId' => $user]
        );
//        dd($cluster);


        return $this->render('inspector/templates/myClusters.html.twig', [
            'controller_name' => 'InspectorController',
            'clusters' => $cluster

        ]);
    }
    /**
     * @Route("/add-favourite/{id}", name="app_inspector_add_favourite")
     */
    public function addFavourite(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cluster = $entity_manager->getRepository(User::class)->find($id);
        $favoriteCluster = new FavoritesClusters();

        $favoriteCluster->setInspectorId($user);
        $favoriteCluster->setClusterId($cluster);

        $entity_manager->persist($favoriteCluster);
        $entity_manager->flush();

        return $this->redirectToRoute('app_inspector_my_clusters');
    }
    /**
     * @Route("/remove-favourite/{id}", name="app_inspector_remove_favourite")
     */
    public function revomeFavourite(Request $request, int $id): Response
    {

        $entity_manager = $this->getDoctrine()->getManager();
        $favoritesClusters = $entity_manager->getRepository(FavoritesClusters::class)->find($id);
//dd($favoritesClusters);
        $entity_manager->remove($favoritesClusters);
        $entity_manager->flush();
        return $this->redirectToRoute('app_inspector_my_clusters');
    }
}
