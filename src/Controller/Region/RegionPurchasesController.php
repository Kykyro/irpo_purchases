<?php

namespace App\Controller\Region;

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
class RegionPurchasesController extends AbstractController
{
    /**
     * @Route("/add-purchases-v2", name="app_add_purchases_v2")
     * @Route("/purchases-edit/{id}", name="app_purchases_edit", methods="GET|POST")
     */
    public function AddPurchases(Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        if(!$this->getUser()->getUserInfo()->isAccessToPurchases())
        {
            return $this->redirectToRoute('app_main');
        }

        $entity_manager = $this->getDoctrine()->getManager();
        $routeName = $request->attributes->get('_route');

        // Настраиваем переменные в зависимости от операции
        if ($routeName == 'app_purchases_edit'){
            $title = 'Редактирование';
            $is_disabled = false;
            $isEdit = true;
        }
        else{
            $title = 'Добавление';
            $is_disabled = false;
            $isEdit = false;
        }


        if($routeName == 'app_purchases_detail' or $routeName == 'app_purchases_edit'){
            $repository = $this->getDoctrine()->getRepository(ProcurementProcedures::class);
            $procurement_procedure = $repository->find($id);

            if(!$procurement_procedure){
                return $this->redirectToRoute("app_main");
            }
        }
        else{
            $procurement_procedure = new ProcurementProcedures();
            $user = $this->getUser();
            $procurement_procedure->setUser($user);
        }


        // генерируем форму
        $form = $this->createForm(purchasesFormType::class, $procurement_procedure);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            if($procurement_procedure->getMethodOfDetermining() === 'Другое')
            {
                $procurement_procedure->setMethodOfDetermining($form['anotherMethodOfDetermining']->getData());
            }
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('purchases_files_directory'),
                        $newFilename
                    );
                    $procurement_procedure->setFileDir($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            $procurement_procedure->setChangeTime(new \DateTime('now'));
            $procurement_procedure->UpdateVersion();

            $entity_manager->persist($procurement_procedure);
            $entity_manager->flush();

            $nextAction = $form->get('saveAndAddNew')->isClicked()
                ? 'app_add_purchases_v2'
                : 'app_main';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('region/templates/form_purchases_v2.html.twig', [
            'controller_name' => 'RegionController',
            'form' => $form->createView(),
            'edit' => $isEdit,
            'title' => $title,
            'method' => $procurement_procedure->getMethodOfDetermining()
        ]);

    }

    private function checkTimeToAccess(){

    }



}
