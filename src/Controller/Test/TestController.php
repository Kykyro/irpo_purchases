<?php

namespace App\Controller\Test;


use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\testformFormType;
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
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add("file", FileType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'cropped-file-upload'],
                'required'   => true,
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        if($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/testform", name="app_testform")
     */
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
