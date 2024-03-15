<?php

namespace App\Controller\BAS;

use App\Entity\EventResult;
use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Entity\UsersEvents;
use App\Form\ChoiceInputType;
use App\Form\purchasesFormType;
use App\Services\FileService;
use App\Services\XlsxService;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
class BASRoadMapController extends AbstractController
{


    /**
     * @Route("/road-map", name="app_bas_road_map")
     */
    public function userCabinet(): Response
    {
        $user = $this->getUser();


        $user_info = $user->getUserInfo();

        return $this->render('BAS_road_map/index.html.twig', [
            'controller_name' => 'DefaultController',
            'user_info' => $user_info,
            'user' => $user
        ]);
    }
    /**
     * @Route("/road-map/event/{id}", name="app_bas_road_map_event_view")
     */
    public function viewEvent(int $id, EntityManagerInterface $em, Request $request, FileService $fileService): Response
    {
        $user = $this->getUser();
        $user_info = $user->getUserInfo();
        $event = $em->getRepository(UsersEvents::class)->find($id);
        $newResult = new EventResult();
        $form = $this->createFormBuilder($newResult)
            ->add('result', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Результат'
            ])
            ->add('commentUser', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Добавить комментарий',
                'required'   => false,
            ])
            ->add('files', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => false,
                'mapped' => false,
                'label' => 'Фаилы',
                'multiple' => true
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            $files = $form->get('files')->getData();

            if ($files) {
                $filesArray = [];
                foreach ($files as $file)
                {
                    array_push($filesArray, $fileService->UploadFile($file, 'event_files_directory'));
                }
                $newResult->setFiles($filesArray);
            }
            $newResult->setStatus('На проверке');
            $em->persist($newResult);
            $event->addEventResult($newResult);
            $em->persist($event);
            $em->flush();
        }

        return $this->render('BAS_road_map/view_event.html.twig', [
            'controller_name' => 'DefaultController',
            'user_info' => $user_info,
            'user' => $user,
            'results' => $event,
            'form' => $form->createView(),

        ]);
    }



}
