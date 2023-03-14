<?php

namespace App\Controller\Landing;

use App\Entity\Files;
use App\Form\ChoiceInputType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use function PHPUnit\Framework\returnArgument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingDocumentationController extends AbstractController
{
    /**
     * @Route("/documentation/{type}", name="app_landing_documentation")
     */
    public function index(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator, string $type): Response
    {
        if($type === 'workshops_files'){
            $title = 'Нормативная документация для создания современных мастерских (учебно-производственных участков)';
        }
        elseif($type === 'cluster_files'){
            $title = 'Нормативная документация образовательно-производственных центров (кластеров)';
        }
        elseif($type === 'little_cluster_files'){
            $title = 'Нормативная документация образовательных кластеров среднего профессионального образования';
        }
        else{
            return $this->redirectToRoute('app_start_landing');
        }
//        dd($type);
        $query = $em->getRepository(Files::class)
            ->createQueryBuilder('a')
            ->where('a.type LIKE :type')
            ->setParameter('type', "$type")
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            40 /*limit per page*/
        );


        return $this->render('landing_documentation/index.html.twig', [
            'controller_name' => 'LandingDocumentationController',
            'pagination' => $pagination,
            'title' => $title
        ]);
    }


}
