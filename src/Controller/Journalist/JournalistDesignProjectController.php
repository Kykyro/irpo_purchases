<?php

namespace App\Controller\Journalist;

use App\Entity\DesignProjectExample;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
class JournalistDesignProjectController extends AbstractController
{
    /**
     * @Route("/new-design-project", name="app_new_design_project")
     * @Route("/edit-design-project/{id}", name="app_edit_design_project")
     */
    public function AddDesignExample(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id){
            $designProject = $entity_manager->getRepository(DesignProjectExample::class)->find($id);
        }
        else{
            $designProject = new DesignProjectExample();
        }

        $form = $this->createFormBuilder($designProject)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'   => false,
            ])
            ->add('file', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'mapped' => false
            ])
            ->add('presentation', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn mt-2'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm();



        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){

            $file = $form->get('file')->getData();
            $presentation = $form->get('presentation')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('design_project_example_directory'),
                        $newFilename
                    );
                    $designProject->setPhoto($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }
            if ($presentation) {
                $originalFilename = pathinfo($presentation->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $presentation->move(
                        $this->getParameter('design_project_example_directory_presentation'),
                        $newFilename
                    );
                    $designProject->setFile($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }

            $entity_manager->persist($designProject);
            $entity_manager->flush();

            return $this->redirectToRoute('app_list_design_project');
        }

        return $this->render('journalist/templates/design_project_example_edit.html.twig', [
            'controller_name' => 'JournalistDesignProjectController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/design-project-list/", name="app_list_design_project")
     */
    public function designExampleList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(DesignProjectExample::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('journalist/templates/design_project_example_list.html.twig', [
            'controller_name' => 'JournalistController',
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/design-project-delete/{id}", name="app_delete_design_project")
     */
    public function designExampleDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(DesignProjectExample::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_list_design_project');
    }
}
