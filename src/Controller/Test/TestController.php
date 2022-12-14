<?php

namespace App\Controller\Test;


use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\testformFormType;
use App\Services\FileService;
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

class TestController extends AbstractController
{
//    /**
//     * @Route("/test", name="app_test")
//     */
    public function index(Request $request, FileService $fileService): Response
    {
        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add("file", FileType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
//            dd('aaaaa');
            $file = $form->get('file')->getData();

            if($file){
                $filename = $fileService->UploadFile($file, 'test_upload_directory');
//                dd($filename);
                $fileService->DeleteFile($filename, 'test_upload_directory');
            }
            return $this->redirectToRoute('app_test');
        }
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView(),
        ]);
    }


    public function testform(Request $request, SluggerInterface $slugger): Response
    {
        $arr = [
            'ChoiceInputType' => 'a'
        ];
        $form = $this->createForm(testformFormType::class, $arr);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
//            dd($form->getData());
            $brochureFile = $form->get('brochure')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }


            }

        }

        return $this->render('test/form.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView()

        ]);
    }
}
