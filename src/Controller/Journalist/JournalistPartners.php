<?php

namespace App\Controller\Journalist;

use App\Entity\DesignProjectExample;
use App\Entity\PartnersLogo;
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
class JournalistPartners extends AbstractController
{
    /**
     * @Route("/new-partners", name="app_new_partners")
     * @Route("/edit-partners/{id}", name="app_edit_partners")
     */
    public function AddPartners(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id){
            $partnersLogo = $entity_manager->getRepository(PartnersLogo::class)->find($id);
        }
        else{
            $partnersLogo = new PartnersLogo();
        }

        $form = $this->createFormBuilder($partnersLogo)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('link', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'   => false,
            ])
            ->add('img', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class)
            ->getForm();



        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){

            $file = $form->get('img')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('partners_logo_directory'),
                        $newFilename
                    );
                    $partnersLogo->setImg($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }


            $entity_manager->persist($partnersLogo);
            $entity_manager->flush();

            return $this->redirectToRoute('app_list_partners');
        }

        return $this->render('journalist/templates/partners_logo_edit.html.twig', [
            'controller_name' => 'JournalistDesignProjectController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/partners-list/", name="app_list_partners")
     */
    public function partnersList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(PartnersLogo::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('journalist/templates/partners_logo_list.html.twig', [
            'controller_name' => 'JournalistController',
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/partners-delete/{id}", name="app_delete_partners")
     */
    public function partnersDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(PartnersLogo::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_list_partners');
    }
}
