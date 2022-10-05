<?php

namespace App\Controller\Journalist;

use App\Entity\Article;
use App\Form\articleEditForm;

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
class JournalistArticleController extends AbstractController
{

    /**
     * @Route("/article-view/{id}", name="app_view_article")
     */
    public function articleView(int $id): Response
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
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // TODO: поменять отрисовку
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
            $articleFiles = $form->get('file')->getData();

            if($articleFiles){
                $originalFilename = pathinfo($articleFiles->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $articleFiles->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $articleFiles->move(
                        $this->getParameter('article_files_directory'),
                        $newFilename
                    );
                    $article->setArticleFile($newFilename);
                } catch (FileException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }



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
                    throw new HttpException(500, $e->getMessage());
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

    /**
     * @Route("/article-delete/{id}", name="app_delete_article")
     */
    public function designExampleDelete(Request $request, int $id): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $designProject = $entity_manager->getRepository(Article::class)->find($id);

        $entity_manager->remove($designProject);
        $entity_manager->flush();

        return $this->redirectToRoute('app_list_article');
    }

}
