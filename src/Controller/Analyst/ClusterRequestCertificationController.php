<?php

namespace App\Controller\Analyst;

use App\Entity\ClustersRequestCertification;
use App\Entity\RfSubject;
use App\Form\clustersRequestsFileForm;
use App\Form\makeCertificateAllForm;
use App\Form\makeCertificateForm;
use App\Services\certificateByClustersService;
use App\Services\ClusterRequestCertificateService;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClusterRequestCertificationController extends AbstractController
{
    /**
     * @Route("analyst/cluster-request-certification", name="app_cluster_request_certification")
     */
    public function index(ClusterRequestCertificateService $certificateService, EntityManagerInterface $em, Request $request, FileService $fileService): Response
    {
        $file = $em->getRepository(ClustersRequestCertification::class)
            ->find(1);
        $uploadForm = $this->createForm(clustersRequestsFileForm::class);
        $uploadForm->handleRequest($request);

        if(!is_null($file)){
            $requests = $certificateService->parse($file->getFile());
        }
        else{
            $requests = null;
        }

        if($uploadForm->isSubmitted() and $uploadForm->isValid())
        {
            $_file = $uploadForm->get('file')->getData();
            if(is_null($file))
            {
                $file = new ClustersRequestCertification();
            }

            if($_file)
            {
                $file->setFile($fileService->UploadFile($_file, 'cluster_request_table_directory'));
                $em->persist($file);
                $em->flush();
            }

        }

        return $this->render('analyst/cluster_request_certification/index.html.twig', [
            'file' => $file,
            'uploadForm' => $uploadForm->createView(),
            'requests' => $requests,
        ]);
    }

    /**
     * @Route("analyst/cluster-request-certification/download/{filename}", name="app_cluster_request_certification_download")
     */
    public function download($filename, ClusterRequestCertificateService $certificateService, Request $request)
    {
        $rows = $request->request->get('cluster');
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('request-download', $submittedToken)) {
            return $certificateService->download($filename, $rows);
        }
        return $this->redirectToRoute('app_cluster_request_certification');
    }

    /**
     * @Route("analyst/certificate-by-clusters", name="app_certificate_by_cluster_analyst")
     */
    public function app_certificate_by_cluster_analyst(Request $request, certificateByClustersService $byClustersService): Response
    {
        $arr = [];
        $entity_manager = $this->getDoctrine()->getManager();
        $regions = $entity_manager->getRepository(RfSubject::class)->findAll();
        $form = $this->createForm(makeCertificateAllForm::class, $arr);

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
                return $byClustersService->getCertificate($data['clusters'], $data['option']);

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
}
