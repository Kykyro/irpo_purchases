<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterDocument;
use App\Entity\ContractCertification;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\clusterDocumentForm;
use App\Form\InspectorPurchasesFindFormType;
use App\Form\inspectorUserEditFormType;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
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
class InspectorContractingCertificateController extends AbstractController
{
    /**
     * @Route("/certificate-approve/{id}", name="app_certificate_approve", methods="GET|POST")
     */
    public function index(Request $request, $id, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('token');
        $contractCertificate = $em->getRepository(ContractCertification::class)->find($id);

        if ($this->isCsrfTokenValid('approve-certification', $submittedToken)) {
            $status = $request->request->get('optionsRadios');
            $contractCertificate->setStatus($status);
            $em->persist($contractCertificate);
            $em->flush();
        }



        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }


}
