<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterDocument;
use App\Entity\RfSubject;
use App\Entity\UGPS;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\clusterDocumentForm;
use App\Form\InspectorPurchasesFindFormType;
use App\Form\inspectorUGPSEditFormType;
use App\Form\inspectorUserEditFormType;
use App\Services\FileService;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorUgpsUserEditController extends AbstractController
{
    /**
     * @Route("/user_ugps_edit/{id}", name="app_inspector_user_ugps_edit", methods="GET|POST")
     */
    public function index(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user_info = $entity_manager->getRepository(UserInfo::class)->find($id);

        $form = $this->createForm(inspectorUGPSEditFormType::class, $user_info);




        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $ugpsList = $user_info->getUGPS();
            $arr = [];
            $key = 1;
            foreach (array_keys($ugpsList) as $i)
            {
                $arr[$key] = $ugpsList[$i];
                $key++;

            }
            $user_info->setUGPS($arr);



            $entity_manager->persist($user_info);
            $entity_manager->flush();

            $user = $entity_manager->getRepository(User::class)
                ->findBy([
                    'user_info' => $user_info,
                ]);
//            return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $user[0]->getId()]);



        }

        return $this->render('inspector/templates/userUgpsEdit.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView(),
            'id' => $id,

        ]);
    }


//    /**
//     * @Route("/a", name="a")
//     */
//    public function purchases(Request $request): Response
//    {
//
//
//    }
}
