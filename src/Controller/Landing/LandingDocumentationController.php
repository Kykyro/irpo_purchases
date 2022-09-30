<?php

namespace App\Controller\Landing;

use App\Entity\Files;
use App\Form\ChoiceInputType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingDocumentationController extends AbstractController
{
    /**
     * @Route("/documentation", name="app_landing_documentation")
     */
    public function index(): Response
    {

        return $this->render('landing_documentation/index.html.twig', [
            'controller_name' => 'LandingDocumentationController',
        ]);
    }


}
