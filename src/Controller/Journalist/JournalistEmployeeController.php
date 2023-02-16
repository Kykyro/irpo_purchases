<?php

namespace App\Controller\Journalist;

use App\Entity\Article;
use App\Entity\Employees;
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
class JournalistEmployeeController extends AbstractController
{
    /**
     * @Route("/employee-list", name="app_journalist_employee_list")
     */
    public function ISList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
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

        $query = $em->getRepository(Employees::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery();


        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $name = $form_data['name'];
            if($form_data['name'] !== null){
                $query = $em->getRepository(Employees::class)
                    ->createQueryBuilder('a')
                    ->where('a.name LIKE :name')
                    ->setParameter('name', "%$name%")
                    ->orderBy('a.id', 'ASC')
                    ->getQuery();
            }
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );



        return $this->render('journalist/templates/employees_list.html.twig', [
            'controller_name' => 'JournalistEmployeeController',
            'form' => $form->createView(),
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/employee-edit/{id}", name="app_journalist_employee_edit")
     * @Route("/employee-add/", name="app_journalist_employee_add")
     */
    public function ISEdit(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id == null){
            $employee = new Employees();
        }
        else{
            $employee = $entity_manager->getRepository(Employees::class)->find($id);
        }


        $form = $this->createFormBuilder($employee)
            ->add("name", TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,

            ])
            ->add("post", TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
            ])
            ->add('file_photo', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'mapped' => false
            ])
            ->add("submit", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $file = $form->get('file_photo')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('employees_photos_directory'),
                        $newFilename
                    );
                    $employee->setPhoto($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }

            $entity_manager->persist($employee);
            $entity_manager->flush();

            return $this->redirectToRoute('app_journalist_employee_list');
        }


        return $this->render('journalist/templates/employees_edit.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView(),
            'file' => $employee->getPhoto(),
        ]);
    }
    /**
     * @Route("/employee-delete/{id}", name="app_delete_employee")
     */
    public function employeeDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(Employees::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_journalist_employee_list');
    }


}
