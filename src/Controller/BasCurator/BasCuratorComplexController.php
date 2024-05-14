<?php

namespace App\Controller\BasCurator;

use App\Entity\EventResult;
use App\Entity\User;
use App\Entity\UsersEvents;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/bas-curator")
 */
class BasCuratorComplexController extends AbstractController
{
    /**
     * @Route("/complex/{id}", name="app_bas_curator_complex")
     */
    public function index(EntityManagerInterface $em, Request $request, int $id): Response
    {

        $user = $em->getRepository(User::class)->find($id);
        $today = new \DateTimeImmutable('now');
        $newEvent = new UsersEvents();

        $form = $this->createFormBuilder($newEvent)
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование',
            ])
            ->add('finishDate', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Срок',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            $newEvent->setType('road_map');
            $em->persist($newEvent);
            $user->addUsersEvent($newEvent);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_bas_curator_complex', ['id'=>$user->getId()]);
        }

        return $this->render('bas_curator_complex/index.html.twig', [
            'controller_name' => 'BasCuratorController',
            'user' => $user,
            'form' => $form->createView(),
            'form_errors' => $form->getErrors(),
            'today' => $today,
        ]);
    }
    /**
     * @Route("/complex/edit-event/{id}", name="app_bas_curator_complex_edit_event")
     */
    public function editEvent(EntityManagerInterface $em, Request $request, int $id): Response
    {

        $event = $em->getRepository(UsersEvents::class)->find($id);
        $user = $event->getUser();

        $form = $this->createFormBuilder($event)
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование',
            ])
            ->add('finishDate', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Срок',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {

            $em->persist($event);

            $em->flush();

            return $this->redirectToRoute('app_bas_curator_complex', ['id'=>$user->getId()]);
        }

        return $this->render('bas_curator_complex/edit_event.html.twig', [
            'controller_name' => 'BasCuratorController',
            'user' => $user,
            'form' => $form->createView(),
            'form_errors' => $form->getErrors(),
        ]);
    }

    /**
     * @Route("/complex/view-event/{id}", name="app_bas_curator_complex_view_event")
     */
    public function viewEvent(EntityManagerInterface $em, Request $request, int $id): Response
    {

        $event = $em->getRepository(UsersEvents::class)->find($id);
        $user = $event->getUser();


        return $this->render('bas_curator_complex/view_event.html.twig', [
            'controller_name' => 'BasCuratorController',
            'user' => $user,
            'event' => $event
        ]);
    }

    /**
     * @Route("/complex/edit-result/{id}", name="app_bas_curator_complex_edit_result")
     */
    public function editResult(EntityManagerInterface $em, Request $request, int $id): Response
    {

        $result = $em->getRepository(EventResult::class)->find($id);
        $user = $result->getUsersEvents()->getUser();

        $form = $this->createFormBuilder($result)
            ->add('commentCurator', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Добавить комментарий'
            ])
            ->add('status', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Статус',
                'choices' => [
                    'На проверке' => 'На проверке',
                    'Не принято' => 'Не принято',
                    'Принято' => 'Принято',
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($result);
            $em->flush();
            return $this->redirectToRoute('app_bas_curator_complex_view_event', ['id' => $result->getUsersEvents()->getId()]);
        }

        return $this->render('bas_curator_complex/edit_result.html.twig', [
            'controller_name' => 'BasCuratorController',
            'user' => $user,
            'result' => $result,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/complex/delete-event/{id}", name="app_bas_curator_complex_delete_event")
     */
    public function deleteEvent(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $event = $em->getRepository(UsersEvents::class)->find($id);

        $event->setDeleted(true);
        $em->persist($event);
        $em->flush($event);

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
