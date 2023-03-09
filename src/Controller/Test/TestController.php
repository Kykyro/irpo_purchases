<?php

namespace App\Controller\Test;


use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Form\ChoiceInputType;
use App\Form\testformFormType;
use App\Services\FileService;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(Request $request,  SerializerInterface $serializer): Response
    {


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',

        ]);
    }

    /**
     * @Route("/test-download", name="app_test_download")
     */
    public function download(Request $request,  SerializerInterface $serializer): Response
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $templateProcessor = new TemplateProcessor('../public/word/Справка_о_контрактации_и_расходовании_средств.docx');
        $templateProcessor->setValues(array('value1' => 'John'));

        $fileName = '_aaaaa'.'.docx';
        $filepath = $templateProcessor->save();

        return $this->file($filepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }




}
