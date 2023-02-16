<?php

namespace App\Controller\Region;

use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use App\Services\FileService;
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
class PurchaseNotesController extends AbstractController
{


    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/purchase-notes", name="app_region_purchase_notes")
     */
    public function purchasesView(Request $request) : Response
    {

        $entity_manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $notes = $entity_manager->getRepository(PurchaseNote::class)
            ->createQueryBuilder('n')
            ->leftJoin('n.purchase', 'p')
            ->andWhere('p.user = :user')

            ->setParameter('user', $user)
            ->orderBy('n.isRead', 'ASC')
            ->getQuery()
            ->getResult();


        return $this->render('region/templates/purchaseNotes.html.twig', [
            'controller_name' => 'RegionController',
            'notes' => $notes

        ]);
    }



}
