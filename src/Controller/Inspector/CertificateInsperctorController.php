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
        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
            return $byClustersService->getCertificate($data['clusters']);
        }

        return $this->render('inspector/certificate_insperctor/index.html.twig', [
            'controller_name' => 'CertificateInsperctorController',
            'form' => $form->createView(),
            'regions' => $regions
        ]);
    }
}
