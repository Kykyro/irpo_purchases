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
class JournalistInfrastrucureSheetsController extends AbstractController
{
    /**
     * @Route("/infrastructure-sheet-list", name="app_journalist_infrastructure_sheet_list")
     */
    public function ISList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add("industry", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => Industry::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $query = $em->getRepository(InfrastructureSheetFiles::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            if($form_data['industry'] !== null){
                $query = $em->getRepository(InfrastructureSheetFiles::class)
                    ->createQueryBuilder('a')
                    ->where('a.industry = :industry')
                    ->setParameter('industry', $form_data['industry']->getId())
                    ->orderBy('a.id', 'ASC')
                    ->getQuery();
            }
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );



        return $this->render('journalist/templates/infrastructure_sheets_list.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/infrastructure-sheet-edit/{id}", name="app_journalist_infrastructure_sheet_edit")
     * @Route("/infrastructure-sheet-add/", name="app_journalist_infrastructure_sheet_add")
     */
    public function ISEdit(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id == null){
            $IS = new InfrastructureSheetFiles();
        }
        else{
            $IS = $entity_manager->getRepository(InfrastructureSheetFiles::class)->find($id);
        }



        $form = $this->createFormBuilder($IS)
            ->add("industry", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => Industry::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add("UGPS", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'class' => UGPS::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
            ])
            ->add('file_IS', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'mapped' => false
            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $file = $form->get('file_IS')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('infrastructure_sheet_files_directory'),
                        $newFilename
                    );
                    $IS->setfile($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }

            $entity_manager->persist($IS);
            $entity_manager->flush();

            return $this->redirectToRoute('app_journalist_infrastructure_sheet_list');
        }


        return $this->render('journalist/templates/infrastructure_sheet_edit.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
            'file' => $IS->getfile(),
        ]);
    }
    /**
     * @Route("/infrastructure-sheet-delete/{id}", name="app_delete_infrastructure_sheet")
     */
    public function ISDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(InfrastructureSheetFiles::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_journalist_infrastructure_sheet_list');
    }


}
