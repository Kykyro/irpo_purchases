<?php

namespace App\Controller\Region;

use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\formWithDate;
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
    public function certificate(Request $request, certificateOfContractingService $certificateOfContractingService) : Response
    {
        $user = $this->getUser();
        $userInfo = $user->getUserInfo();
        $arr = [];

        $form = $this->createFormBuilder($arr)
            ->add('ExtraFundsEconomicSector', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                ],
                'label' => 'Средства организаций реального сектора экономики',
                'data' => $userInfo->getExtraFundsEconomicSector() * 1000
            ])
            ->add('FinancingFundsOfSubject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'label' => 'Средства субъекта РФ',
                'data' => $userInfo->getFinancingFundsOfSubject() * 1000
            ])
            ->add('ExtraFundsOO', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'label' => 'Средства образовательной организации',
                'data' => $userInfo->getExtraFundsOO() * 1000,
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            return $certificateOfContractingService->generateSertificate($user->getId(), null, $form->getData());
        }

        return $this->render('region/templates/certificates.html.twig', [
            'controller_name' => 'RegionController',
            'user' => $user,
            'form' => $form->createView(),

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
