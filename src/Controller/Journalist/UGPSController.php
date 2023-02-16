<?php

namespace App\Controller\Journalist;

use App\Entity\Article;
use App\Entity\Industry;
use App\Entity\InfrastructureSheetFiles;
use App\Entity\Regions;
use App\Entity\UGPS;
use App\Form\articleEditForm;
use App\Form\mapEditForm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class JournalistController
 * @package App\Controller
 * @Route("/journalist")
 */
class UGPSController extends AbstractController
{
    /**
     * @Route("/UGPS-list", name="app_journalist_UGPS_list")
     */
    public function UGPSList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add("name", TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,

            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $query = $em->getRepository(UGPS::class)
            ->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            if($form_data['name'] !== null){
                $name = $form_data['name'];
                $query = $em->getRepository(UGPS::class)
                    ->createQueryBuilder('a')
                    ->andWhere('a.name like :name')
                    ->setParameter('name', "%$name%")
                    ->getQuery();

            }
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );



        return $this->render('journalist/templates/UGPS_list.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/UGPS-edit/{id}", name="app_journalist_UGPS_edit")
     * @Route("/UGPS-add/", name="app_journalist_UGPS_add")
     */
    public function UGPSEdit(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id == null){
            $UGPS = new UGPS();
        }
        else{
            $UGPS = $entity_manager->getRepository(UGPS::class)->find($id);
        }



        $form = $this->createFormBuilder($UGPS)

            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){


            $entity_manager->persist($UGPS);
            $entity_manager->flush();

            return $this->redirectToRoute('app_journalist_UGPS_list');
        }


        return $this->render('journalist/templates/UGPS_edit.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
        ]);
    }



}
