<?php

namespace App\Controller\Journalist;

use App\Entity\Files;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class JournalistController
 * @package App\Controller
 * @Route("/journalist")
 */
class JournalistDocumentController extends AbstractController
{



    /**
     * @Route("/new-document", name="app_new_document")
     * @Route("/edit-document/{id}", name="app_edit_document")
     */
    public function AddDocument(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id){
            $doc = $entity_manager->getRepository(Files::class)->find($id);
        }
        else{
            $doc = new Files();
        }

        $form = $this->createFormBuilder($doc)
            ->add('name', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Нормативная документация образовательно-производственных центров (кластеров)' => 'cluster_files',
                    'Типовые инфраструктурные листы для создания современных мастерских (учебно-производственных участков)' => 'workshops_files',
                    'Нормативная документация образовательных кластеров среднего профессионального образования' => 'little_cluster_files',
                    'Cпециализированные классы  (кружки)  и центры практической подготовки в сфере беспилотных авиационных систем' => 'specialized_classes_files',
                ]
            ])
            ->add('file_doc', FileType::class, [
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class)
            ->getForm();



        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){

            $file = $form->get('file_doc')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('documents_files_directory'),
                        $newFilename
                    );
                    $doc->setFile($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }


            $entity_manager->persist($doc);
            $entity_manager->flush();

            return $this->redirectToRoute('app_journalist');
        }

        return $this->render('journalist/templates/documents_edit.html.twig', [
            'controller_name' => 'LandingDocumentationController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/document-list/", name="app_list_document")
     */
    public function documentList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(Files::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('journalist/templates/document_list.html.twig', [
            'controller_name' => 'JournalistController',
            'pagination' => $pagination
        ]);
    }
    /**
     * @Route("/document-delete/{id}", name="app_delete_document")
     */
    public function documentDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(Files::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_list_document');
    }
}
