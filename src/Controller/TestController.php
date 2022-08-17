<?php

namespace App\Controller;


use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\testformFormType;
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

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/testform", name="app_testform")
     */
    public function testform(Request $request): Response
    {
        $arr = [
            'ChoiceInputType' => 'a'
        ];
        $form = $this->createForm(testformFormType::class, $arr);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dd($form->getData());

        }

        return $this->render('test/form.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView()

        ]);
    }
}
