<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Regions;
use App\Form\articleEditForm;
use App\Form\mapEditForm;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
class JournalistController extends AbstractController
{
    /**
     * @Route("/menu", name="app_journalist")
     */
    public function index(Request $request): Response
    {

        return $this->render('journalist/templates/menu.html.twig', [
            'controller_name' => 'JournalistController',

        ]);
    }

    /**
     * @Route("/map-edit/{id}", name="app_map_edit")
     */
    public function map(Request $request, int $id = 0): Response
    {
        if($id){

            $entity_manager = $this->getDoctrine()->getManager();
            $map = $this->getDoctrine()->getRepository(Regions::class)->find($id);
            $form = $this->createForm(mapEditForm::class, $map);
            $form->handleRequest($request);

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $org = $map->getOrganization();
            $orgContent = $serializer->serialize($org, 'json');
            $orgContent = stripslashes($orgContent);
            $orgContent = trim($orgContent, '["]');

            if($form->isSubmitted() && $form->isValid()){
                $organization = $form->get('organization')->getData();
                if($organization){

                    $map->setOrganization(array(trim(preg_replace('/\s\s+/', '', $organization))));
                }
                $entity_manager->persist($map);
                $entity_manager->flush();

                return $this->redirectToRoute('app_map_edit');

            }
            return $this->render('journalist/templates/map.html.twig', [
                'controller_name' => 'JournalistController',
                'form' => $form->createView(),
                'orgContent' => $orgContent,
            ]);
        }
        else{
            $map = $this->getDoctrine()->getRepository(Regions::class)
                ->createQueryBuilder('a')
                ->orderBy('a.name', 'ASC')
                ->getQuery()
                ->getResult();



            return $this->render('journalist/templates/map.html.twig', [
                'controller_name' => 'JournalistController',
                'map' => $map
            ]);
        }

    }
    /**
     * @Route("/cropper", name="app_cropper")
     */
    public function imgCropper(Request $request): Response
    {

        return $this->render('journalist/templates/cropper.html.twig', [
            'controller_name' => 'JournalistController',

        ]);
    }

    /**
     * @Route("/article-view/{id}", name="app_view_article")
     */
    public function articleView(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $article = $entity_manager->getRepository(Article::class)->find($id);


        return $this->render('journalist/templates/article_view.html.twig', [
            'controller_name' => 'JournalistController',
            'article' => $article
        ]);
    }
    /**
     * @Route("/article-list/", name="app_list_article")
     */
    public function articleList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $em->getRepository(Article::class)
            ->createQueryBuilder('a')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('journalist/templates/article_list.html.twig', [
            'controller_name' => 'JournalistController',
            'pagination' => $pagination
        ]);
    }
    /**
     * @Route("/new-article", name="app_new_article")
     * @Route("/edit-article/{id}", name="app_edit_article")
     */
    public function newArticle(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        if($id){
            $article = $entity_manager->getRepository(Article::class)->find($id);
        }
        else{
            $article = new Article();
        }


        $form = $this->createForm(articleEditForm::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('imgTitle')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('article_title_img_directory'),
                        $newFilename
                    );
                    $article->setImg($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $entity_manager->persist($article);
            $entity_manager->flush();

            return $this->redirectToRoute('app_journalist');
        }

        return $this->render('journalist/templates/article_edit.html.twig', [
            'controller_name' => 'JournalistController',
            'form' => $form->createView()
        ]);
    }
}
