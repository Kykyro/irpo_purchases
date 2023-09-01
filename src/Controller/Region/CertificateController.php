<?php

namespace App\Controller\Region;

use App\Entity\CertificateFunds;
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
    public function certificate(Request $request, certificateOfContractingService $certificateOfContractingService, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $userInfo = $user->getUserInfo();
        $statusLib = [
            'Справка не прислана' => '-danger',
            'Файл на проверке' => '-warning',
            'Новый файл на проверке' => '-warning',
            'Отправлен на доработку' => '-danger',
            'Справка принята' => '-primary',

        ];
        if(is_null($userInfo->getCertificateFunds()))
        {
            $certificateFunds = new CertificateFunds($userInfo);
            $userInfo->setCertificateFunds($certificateFunds);

        }
        else
        {
            $certificateFunds = $userInfo->getCertificateFunds();
        }

        $contractCertificate = $userInfo->getContractCertifications();
        if(count($contractCertificate))
            $contractCertificate = $contractCertificate->last();
        else
            $contractCertificate = null;

        $arr = [];

        $form = $this->createFormBuilder($arr)
            ->add('ExtraFundsEconomicSector', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                        'min' => $userInfo->getExtraFundsEconomicSector() ,
                        'max' => '99999999999'
                ],
                'label' => 'Средства организаций реального сектора экономики',
                'data' => $certificateFunds->getEconomicFunds()
            ])
            ->add('FinancingFundsOfSubject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => $userInfo->getFinancingFundsOfSubject() * 1000,
                    'max' => '99999999999'
                ],
                'label' => 'Средства субъекта РФ',
                'data' => $certificateFunds->getSubjectFunds()
            ])
            ->add('ExtraFundsOO', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => $userInfo->getExtraFundsOO() * 1000,
                    'max' => '99999999999'
                ],
                'label' => 'Средства образовательной организации',
                'data' => $certificateFunds->getExtraFunds() ,
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();

            $certificateFunds->setEconomicFunds($data['ExtraFundsEconomicSector']);
            $certificateFunds->setSubjectFunds($data['FinancingFundsOfSubject']);
            $certificateFunds->setExtraFunds($data['ExtraFundsOO']);

            $em->persist($userInfo);
            $em->persist($certificateFunds);
            $em->flush();

            return $certificateOfContractingService->generateSertificate($user->getId(), null, $data);
        }

        return $this->render('region/templates/certificates.html.twig', [
            'controller_name' => 'RegionController',
            'user' => $user,
            'form' => $form->createView(),
            'contract_certificate' => $contractCertificate,
            'status_lib' => $statusLib,

        ]);
    }

    /**
     * @Route("/certificate-download/{id}", name="app_region_certificate_download")
     */
    public function downloadCertificate(certificateOfContractingService $certificateOfContractingService, int $id)
    {
        return $certificateOfContractingService->generateSertificate($id);
    }

    /**
     * @Route("/certificate-upload/", name="app_region_certificate_upload")
     */
    public function uploadCertificate(Request $request, FileService $fileService, EntityManagerInterface $em)
    {
        $submittedToken = $request->request->get('token');
        $user = $this->getUser();
        $userInfo = $user->getUserInfo();

        if ($this->isCsrfTokenValid('upload-certification', $submittedToken)) {
            $contractCertificate = $userInfo->getContractCertifications()->last();
            if($request->files->get('file'))
            {
                if($contractCertificate->getFile())
                {
                    $contractCertificate->setStatus('Новый файл на проверке');
                    $fileService->DeleteFile($contractCertificate->getFile(), 'certificate_files_directory');
                }
                else
                    $contractCertificate->setStatus('Файл на проверке');


                $contractCertificate->setFile($fileService->UploadFile($request->files->get('file'), 'certificate_files_directory'));

            }


            $em->persist($contractCertificate);
            $em->flush();


        }
        return $this->redirectToRoute("app_region_certificate");
    }




}
