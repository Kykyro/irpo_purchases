<?php

namespace App\Controller\Test;


use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\RepairDump;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Form\ChoiceInputType;
use App\Form\makeCertificateForm;
use App\Form\purchasesFormType;
use App\Form\testformFormType;
use App\Services\certificateByClustersService;
use App\Services\FileService;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
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
    private $serializer;
//    private $entity_manager;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
//        $this->entity_manager = $em;

//        parent::__construct();
    }
    /**
     * @Route("/test", name="app_test")
     * @Route("/test/{id}", name="app_test_id")
     */
    public function index(Request $request, int $id = null, certificateByClustersService $byClustersService): Response
    {

//        $entity_manager = $this->getDoctrine()->getManager();
//        $user = $entity_manager->getRepository(User::class)->find($id);
//        $adreses = $user->getClusterAddresses();
//        foreach ($adreses as $adrese)
//        {
//            $zones = $adrese->getSortedClusterZones();
//            foreach ($zones as $zone)
//            {
//                $repair = $zone->getZoneRepair();
//                $jsonContent =  $this->serializer->serialize($repair, 'json',['groups' => ['dump_data']]);
//                $jsonContent = utf8_encode($jsonContent);
////                dump($zone->getId());
////                dump($jsonContent);
//                $dump = new RepairDump();
//                $dump->setRepair($repair);
//                $dump->setDump($jsonContent);
//                dump($dump);
//
//            }
//
//        }
        $email = (new Email())
            ->from('support@mtb-spo.ru')
            ->to('vova.199@mail.ru')
            ->subject('Hello!')
            ->text('test message')
            ;

        $dns = 'smtp://mailhog:1025';
        $transport = Transport::fromDsn($dns);
        $mailer = new Mailer($transport);
        $mailer->send($email);
        dd();


        return $this->render('test/index.html.twig', [
            'controller_name' => 'RegionController',



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
