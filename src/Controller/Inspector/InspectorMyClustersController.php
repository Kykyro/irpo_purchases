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
class InspectorMyClustersController extends AbstractController
{
    /**
     * @Route("/my-clusters", name="app_inspector_my_clusters")
     */
    public function index(Request $request): Response
    {




        return $this->render('inspector/templates/myClusters.html.twig', [
            'controller_name' => 'InspectorController'

        ]);
    }
    /**
     * @Route("/add-favourite", name="app_inspector_add_favourite")
     */
    public function addFavourite(Request $request): Response
    {




        return $this->render('inspector/templates/myClusters.html.twig', [
            'controller_name' => 'InspectorController'

        ]);
    }

}
