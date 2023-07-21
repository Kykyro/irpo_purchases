<?php

namespace App\Controller\Analyst;

use App\Entity\ClustersRequestCertification;
use App\Form\clustersRequestsFileForm;
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
            $request = null;
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
}
