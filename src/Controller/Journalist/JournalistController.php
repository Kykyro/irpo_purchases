<?php

namespace App\Controller\Journalist;



use App\Entity\Regions;
use App\Form\mapEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class JournalistController
 * @package App\Controller
 * @Route("/journalist")
 */
class JournalistController extends AbstractController
{

    /**
     * @Route("/menu", name="app_journalist")
     */
    public function index(): Response
    {

        return $this->render('journalist/templates/menu.html.twig', [
            'controller_name' => 'JournalistController',

        ]);
    }

    /**
     * @Route("/map-edit/{id}", name="app_map_edit")
     */
    public function map(Request $request, int $id = 0): Response
    {
        if($id){

            $entity_manager = $this->getDoctrine()->getManager();
            $map = $this->getDoctrine()->getRepository(Regions::class)->find($id);
            $form = $this->createForm(mapEditForm::class, $map);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $organization = $form->get('organization')->getData();
                if($organization){

                    $map->setOrganization(array(trim(preg_replace('/\s\s+/', '', $organization))));
                }
                $entity_manager->persist($map);
                $entity_manager->flush();

                return $this->redirectToRoute('app_map_edit');

            }
            return $this->render('journalist/templates/map.html.twig', [
                'controller_name' => 'JournalistController',
                'form' => $form->createView(),
            ]);
        }
        else{
            $map = $this->getDoctrine()->getRepository(Regions::class)
                ->createQueryBuilder('a')
                ->orderBy('a.name', 'ASC')
                ->getQuery()
                ->getResult();

            return $this->render('journalist/templates/map.html.twig', [
                'controller_name' => 'JournalistController',
                'map' => $map
            ]);
        }

    }
//    /**
//     * @Route("/cropper", name="app_cropper")
//     */
//    public function imgCropper(): Response
//    {
//
//        return $this->render('journalist/templates/cropper.html.twig', [
//            'controller_name' => 'JournalistController',
//
//        ]);
//    }


}
