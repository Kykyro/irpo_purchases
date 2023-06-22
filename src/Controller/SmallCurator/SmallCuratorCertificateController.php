<?php

namespace App\Controller\SmallCurator;

use App\Form\makeCertificateSmallClustersForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\certificateByClustersService;
use App\Entity\RfSubject;
use App\Form\makeCertificateForm;

/**
 * @Route("/inspector")
 */
class SmallCuratorCertificateController extends AbstractController
{
    /**
     * @Route("/small-clusters/certificate-by-clusters", name="app_certificate_by_cluster_insperctor_sc")
     */
    public function index(Request $request, certificateByClustersService $byClustersService): Response
    {
        $arr = [];
        $entity_manager = $this->getDoctrine()->getManager();
        $regions = $entity_manager->getRepository(RfSubject::class)->findAll();
        $form = $this->createForm(makeCertificateSmallClustersForm::class, $arr);

        $form->handleRequest($request);
        if($form->isSubmitted() )
        {
            $data = $form->getData();
            $ugps = [];
            if($form->get('as_choose')->getData())
            {
                $ugps = $form->get('UGPS')->getErrors()[0]->getMessageParameters()['{{ value }}'];
                $ugps = explode(',',$ugps);
                for($i = 0; $i < count($ugps); $i++)
                {
                    $ugps[$i] = trim(str_replace('"', '', $ugps[$i]));
                }

            }

//            dd($ugps);
            if(!$form->get('download_as_table')->getData())
            {

                $ugps = in_array('ugps', $data['option']);
                $zone = in_array('zone', $data['option']);
                return $byClustersService->getCertificate($data['clusters'], $data['option']);

            }
            else
            {

                return $byClustersService->getTableCertificate($data['clusters'], $ugps);
            }
        }

        return $this->render('inspector/certificate_insperctor/index.html.twig', [
            'controller_name' => 'SmallCuratorCertificateController',
            'form' => $form->createView(),
            'regions' => $regions
        ]);
    }
}
