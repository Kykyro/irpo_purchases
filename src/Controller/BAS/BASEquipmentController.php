<?php

namespace App\Controller\BAS;

use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Entity\UAVsCertificate;
use App\Form\BASequipment\equipmentBasForm;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use App\Services\BAS\UAVsEquipmentTableService;
use App\Services\XlsxService;
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
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;



/**
 * @Route("/bas")
 */
class BASEquipmentController extends AbstractController
{


    /**
     * @Route("/equipment", name="app_bas_equipment_edit")
     */
    public function equipmentEdit(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(equipmentBasForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $UAVsCertificate = $user->getUAVsCertificate();
            if(is_null($UAVsCertificate))
            {
                $UAVsCertificate = new UAVsCertificate();
                $user->setUAVsCertificate($UAVsCertificate);
            }

            $UAVsCertificate->setStatus('Справка на проверке');
            $em->persist($user);
            $em->flush();
        }

        return $this->render('BAS/equipment.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/equipment/download", name="app_bas_equipment_download")
     */
    public function download(UAVsEquipmentTableService $service)
    {
        $user = $this->getUser();
        return $service->downloadTable($user);
    }



}
