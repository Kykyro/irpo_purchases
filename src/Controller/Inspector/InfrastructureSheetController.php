<?php

namespace App\Controller\Inspector;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\InspectorPurchasesFindFormType;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
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
class InfrastructureSheetController extends AbstractController
{
    /**
     * @Route("/infrastructure-sheet", name="app_inspector_infrastructure_sheet", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $regions = $entity_manager->getRepository(User::class)->findBy(
            ['roles' => 'REGION_ROLE']
        );



        return $this->render('inspector/templates/infrastructure_sheet.html.twig', [
            'controller_name' => 'InspectorController',

        ]);
    }


}
