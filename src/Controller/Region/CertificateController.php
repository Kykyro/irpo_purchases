<?php

namespace App\Controller\Region;

use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use App\Services\certificateOfContractingService;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\String\Slugger\SluggerInterface;



/**
 * @Route("/region")
 */
class CertificateController extends AbstractController
{


    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/certificate", name="app_region_certificate")
     */
    public function certificate(Request $request,  EntityManagerInterface $em, PaginatorInterface $paginator) : Response
    {

        $user = $this->getUser();

        return $this->render('region/templates/certificates.html.twig', [
            'controller_name' => 'RegionController',
            'user' => $user
        ]);
    }

    /**
     * @Route("/certificate-download/{id}", name="app_region_certificate_download")
     */
    public function downloadCertificate(certificateOfContractingService $certificateOfContractingService, int $id)
    {
        return $certificateOfContractingService->generateSertificate($id);
    }



}
