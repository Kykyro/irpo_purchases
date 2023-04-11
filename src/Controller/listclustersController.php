<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class listclustersController extends AbstractController
{
    /**
     * @Route("//listclusters", name="app__listclusters")
     */
    public function index(): Response
    {
        return $this->render('listclusters/index.html.twig', [
            'controller_name' => 'listclustersController',
        ]);
    }
}
