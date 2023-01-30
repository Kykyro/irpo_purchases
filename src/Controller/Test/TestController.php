<?php

namespace App\Controller\Test;


use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
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

        $entity_manager = $this->getDoctrine()->getManager();

        $arr = [];
        $form = $this->createFormBuilder($arr)
            ->add('clusters', EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.uuid', 'ASC');
                },
                'choice_label' => 'uuid',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        $jsonContent ="";
        $array_ =[];
        $sum = 0;
        $finSum = 0;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($data['clusters'] as $cluster){
            $pp = $entity_manager->getRepository(ProcurementProcedures::class)
                ->createQueryBuilder('a')
                ->andWhere('a.user = :uid')
                ->setParameter('uid', $cluster->getId())
                ->getQuery()
                ->getResult();

            }
            $jsonContent = $serializer->serialize($pp, 'json',['groups' => ['dump_data']]);

            $jsonContent = utf8_encode($jsonContent);
            $array_ = $serializer->deserialize($jsonContent, 'App\Entity\ProcurementProcedures[]' , 'json');
            $sum = $this->getNMCK($array_);
            $finSum = $this->getFinSum($array_);
        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView(),
            'json' => $jsonContent,
            'data' => $array_,
            'sum' => $sum,
            'finSum' => $finSum
        ]);
    }

    private function getNMCK(array $arr){
        $sum = 0;
        foreach ($arr as $item){
            $sum += $item->getNMCK();
        }
        return $sum;
    }

    private  function getFinSum(array $arr){
        $sum = 0;
        foreach ($arr as $item){
            $sum += $item->getContractCost();
        }
        return $sum;
    }


}
