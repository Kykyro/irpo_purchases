<?php

namespace App\Controller\Region;

use App\Entity\AnotherDocument;
use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Form\cancelPurchasesForm;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function AddPurchases(Request $request, SluggerInterface $slugger, int $id = null, FileService $fileService): Response
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

        // получаем файлы
        $file_dir = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :file')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('file', "%fileDir%")
            ->getQuery()
            ->getResult();
        $paymentOrder = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :payment')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('payment', "%paymentOrder%")
            ->getQuery()
            ->getResult();
        $closingDocument = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :closing')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('closing', "%closingDocument%")
            ->getQuery()
            ->getResult();
        $additionalAgreement = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :add')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('add', "additionalAgreement%")
            ->getQuery()
            ->getResult();

        // генерируем форму
        $form = $this->createForm(purchasesFormType::class, $procurement_procedure);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            $closingDocument_file = $form->get('closingDocument_file')->getData();
            $paymentOrder_file = $form->get('paymentOrder_file')->getData();
            $additionalAgreement_file = $form->get('AdditionalAgreement_file')->getData();
            if($procurement_procedure->getMethodOfDetermining() === 'Другое')
            {
                $procurement_procedure->setMethodOfDetermining($form['anotherMethodOfDetermining']->getData());
            }
            if($file)
                $procurement_procedure->setFileDir($fileService->UploadFile($file, 'purchases_files_directory'));
            if($closingDocument_file)
                $procurement_procedure->setClosingDocument($fileService->UploadFile($closingDocument_file, 'closing_files_directory'));
            if($paymentOrder_file)
                $procurement_procedure->setPaymentOrder($fileService->UploadFile($paymentOrder_file, 'payment_orders_directory'));
            if($additionalAgreement_file)
                $procurement_procedure->setAdditionalAgreement($fileService->UploadFile($additionalAgreement_file, 'additional_agreement_directory'));
            $procurement_procedure->setChangeTime(new \DateTime('now'));
            $procurement_procedure->UpdateVersion();

            $anotherDocument = $form->get('anotherDocument')->getData();
            foreach ($anotherDocument as $doc)
            {
                $filename = $fileService->UploadFile($doc, 'another_documents_directory');
                $_photo = new AnotherDocument();
                $_photo->setFile($filename);
                $_photo->setDate(new \DateTimeImmutable('now'));
                $entity_manager->persist($_photo);
                $procurement_procedure->addAnotherDocument($_photo);
            }
            $procurement_procedure->setIsRead(false);
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
            'method' => $procurement_procedure->getMethodOfDetermining(),
            'file' => $file_dir,
            'paymentOrder' => $paymentOrder,
            'closingDocument' => $closingDocument,
            'additionalAgreement' => $additionalAgreement,
            'pp' => $procurement_procedure,
            'errors' => $form->getErrors(true)

        ]);

    }

    private function checkTimeToAccess(){

    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/purchases-notes/check/{id}", name="app_purchases_notes_check")
     */
    public function purchasesNotesCheck(Request $request, int $id, EntityManagerInterface $em) : Response
    {
        $note = $em
            ->getRepository(PurchaseNote::class)
            ->find($id);
        $note->setIsRead(true);

        $em->persist($note);
        $em->flush();

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/purchases/{id}", name="app_purchases_detail")
     */
    public function purchasesView(Request $request, int $id) : Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $title = 'Просмотр';
        $purchase = $this->getDoctrine()
            ->getRepository(ProcurementProcedures::class)
            ->find($id);

        $current_user = $this->getUser()->getUserIdentifier();

        $notes = $purchase->getPurchaseNotes();

//        foreach ($notes as $note)
//        {
//            if(!$note->isIsRead())
//            {
//                $note->setIsRead(true);
//                $entity_manager->persist($note);
//            }
//        }
        $entity_manager->flush();
        // получаем файлы
        $file_dir = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :file')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('file', "%fileDir%")
            ->getQuery()
            ->getResult();
        $paymentOrder = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :payment')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('payment', "%paymentOrder%")
            ->getQuery()
            ->getResult();
        $closingDocument = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :closing')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('closing', "%closingDocument%")
            ->getQuery()
            ->getResult();
        $additionalAgreement = $entity_manager->getRepository(Log::class)
            ->createQueryBuilder('l')
            ->andWhere('l.field_name LIKE :add')
            ->andWhere('l.foreign_key = :key')
            ->setParameter('key', "$id")
            ->setParameter('add', "%additionalAgreement%")
            ->getQuery()
            ->getResult();

        if($purchase->getUser()->getUserIdentifier() != $current_user or $purchase->getIsDeleted())
        {
            return $this->redirectToRoute('app_main');
        }
        $delete = [];
        $form = $this->createFormBuilder($delete)
            ->add('reason', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger'
                ],
                'label' => 'Удалить'
            ])
            ->getForm();
        $cancelForm = $this->createForm(cancelPurchasesForm::class, $purchase);


        $form->handleRequest($request);
        $cancelForm->handleRequest($request);





        if($form->isSubmitted() && $form->isValid()){
            $entity_manager = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $purchase->setIsDeleted(true);
            $purchase->setDeleteReason($data['reason']);
            $entity_manager->persist($purchase);
            $entity_manager->flush();
            return $this->redirectToRoute('app_main');
        }

        if($cancelForm->isSubmitted() && $cancelForm->isValid()) {
            $purchase->setIsCancelled(true);
            $entity_manager->persist($purchase);
            $entity_manager->flush();
            return $this->redirectToRoute('app_main');
        }

        return $this->render('purchases_detail/purchases_view.html.twig', [
            'controller_name' => 'RegionController',
            'title' => $title,
            'purchase' => $purchase,
            'versionInfo' => $purchase->getVersionInfoAndDate(),
            'form' => $form->createView(),
            'file' => $file_dir,
            'paymentOrder' => $paymentOrder,
            'closingDocument' => $closingDocument,
            'additionalAgreement' => $additionalAgreement,
            'notes' => $notes,
            'cancelledForm' => $cancelForm->createView(),

        ]);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/purchases-is-not-cancelled/{id}", name="app_purchases_is_not_cancelled")
     */
    public function purchaseIsNotCancelled(Request $request, int $id) : Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $purchases = $entity_manager->getRepository(ProcurementProcedures::class)->find($id);

        $purchases->setIsCancelled(false);

        $entity_manager->persist($purchases);
        $entity_manager->flush();

        return $this->redirectToRoute('app_main');
    }

}
