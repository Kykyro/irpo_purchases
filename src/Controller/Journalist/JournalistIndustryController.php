<?php

namespace App\Controller\Journalist;

use App\Entity\Article;
use App\Entity\Industry;
use App\Entity\InfrastructureSheetFiles;
use App\Entity\Regions;
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
class JournalistIndustryController extends AbstractController
{


    /**
     * @Route("/industry-add", name="app_journalist_industry_add")
     * @Route("/industry-edit/{id}", name="app_journalist_industry_edit")
     */
    public function industryAdd (Request $request, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id){
            $industry = $entity_manager->getRepository(Industry::class)->find($id);
        }
        else{
            $industry = new Industry();
        }

        $form = $this->createFormBuilder($industry)
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entity_manager->persist($industry);
            $entity_manager->flush();

            return $this->redirectToRoute('app_journalist_industry_list');
        }
        return $this->render('journalist/templates/industry_edit.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/industry-list", name="app_journalist_industry_list")
     */
    public function industryList (Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
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

        $query = $em->getRepository(Industry::class)
            ->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            if($form_data['name'] !== null){
                $name = $form_data['name'];
                $query = $em->getRepository(Industry::class)
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



        return $this->render('journalist/templates/industry_list.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/industry-delete/{id}", name="app_delete_industry")
     */
    public function industryDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(Industry::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_journalist_industry_list');
    }

}
