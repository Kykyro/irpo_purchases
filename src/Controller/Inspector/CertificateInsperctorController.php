<?php

namespace App\Controller\Inspector;

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
class CertificateInsperctorController extends AbstractController
{
    /**
     * @Route("/certificate-by-clusters", name="app_certificate_by_cluster_insperctor")
     */
    public function index(Request $request, certificateByClustersService $byClustersService): Response
    {
        $arr = [];
        $entity_manager = $this->getDoctrine()->getManager();
        $regions = $entity_manager->getRepository(RfSubject::class)->findAll();
        $form = $this->createForm(makeCertificateForm::class, $arr);

        $form->handleRequest($request);
        if($form->isSubmitted() )
        {
            $data = $form->getData();
            $ugps = [];
            $employeers = [];
            $zones = [];
            if($form->get('as_choose')->getData())
            {
//                dd($form);
                if(count($form->get('UGPS')->getErrors()) > 0)
                    $ugps = $this->getFromError($form->get('UGPS')->getErrors()[0]->getMessageParameters()['{{ value }}']);
                if(count($form->get('employers')->getErrors()) > 0)
                    $employeers = $this->getFromError($form->get('employers')->getErrors()[0]->getMessageParameters()['{{ value }}']);
                if(count($form->get('zones')->getErrors()) > 0)
                    $zones = $this->getFromError($form->get('zones')->getErrors()[0]->getMessageParameters()['{{ value }}']);


            }

//            dd($ugps);
            if(!$form->get('download_as_table')->getData())
            {

                $ugps = in_array('ugps', $data['option']);
                $zone = in_array('zone', $data['option']);
                return $byClustersService->getCertificate($data['clusters'], $ugps, $zone);

            }
            else
            {

                return $byClustersService->getTableCertificate($data['clusters'], $ugps, $employeers, $zones);
            }
        }

        return $this->render('inspector/certificate_insperctor/index.html.twig', [
            'controller_name' => 'CertificateInsperctorController',
            'form' => $form->createView(),
            'regions' => $regions
        ]);
    }

    public function getFromError($arr)
    {
        $arr = explode(',', $arr);
        for($i = 0; $i < count($arr); $i++)
        {
            $arr[$i] = trim(str_replace('"', '', $arr[$i]));
        }
        return $arr;
    }

}
