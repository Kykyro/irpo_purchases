<?php

namespace App\Controller\Region;

use App\Entity\InfrastructureSheetRegionFile;
use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\String\Slugger\SluggerInterface;



/**
 * @Route("/region")
 */
class RegionInfrastructureSheetController extends AbstractController
{


    /**
     * @Route("/region-is", name="app_region_is")
     */
    public function regionIS(): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $infrastructureSheets = $entity_manager->getRepository(InfrastructureSheetRegionFile::class)->findBy([
           'user' => $user,

        ], [
            'id' => 'DESC'
        ]);


        return $this->render('region/templates/infrastructure_sheet.html.twig', [
            'controller_name' => 'RegionController',
            'infrastructureSheets' => $infrastructureSheets
        ]);
    }
}
