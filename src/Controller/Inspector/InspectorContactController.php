<?php

namespace App\Controller\Inspector;

use App\Entity\ClusterContact;
use App\Entity\ClusterDocument;
use App\Entity\Log;
use App\Entity\MonitoringCheckOut;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\clusterDocumentForm;
use App\Form\InspectorPurchasesFindFormType;
use App\Form\inspectorUserEditFormType;
use App\Services\Contacts\ContactXlsxService;
use App\Services\FileService;
use App\Services\XlsxRepairNeededService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class InspectorContactController extends AbstractController
{
    /**
     * @Route("/contacts", name="app_inspector_contacts")
     */
    public function index(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {

        $form = $this->createFormBuilder()
            ->add('search', TextType::class,[
                'attr' => [
                    'class' => 'form-control col-md-4',
                ],
                'label' => false,
                'required' => false
            ])
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);

        $query = $em->getRepository(ClusterContact::class)
            ->createQueryBuilder('p')

            ->orderBy('p.id', 'DESC')
            ;
        if($form->isSubmitted() and $form->isValid())
        {
            $search = $form->getData()['search'];

            $query = $query
                ->leftJoin('p.user', 'u')
                ->leftJoin('u.user_info', 'uf')
                ->andWhere('p.Name LIKE :search or p.phoneNumber LIKE :search or uf.educational_organization LIKE :search')
                ->setParameter('search', "%$search%");

        }

        $query = $query->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            50 /*limit per page*/
        );


        return $this->render('inspector_contacts/index.html.twig', [
            'controller_name' => 'InspectorController',
            'pagination' => $pagination,
            'form' => $form->createView()

        ]);
    }


    /**
     * @Route("/download-contacts", name="app_inspector_download_contacts")
     */
    public function downloadContact(ContactXlsxService $service): Response
    {
        return $service->generate();
    }


    /**
     * @Route("/remove-contact/{id}", name="app_inspector_remove_contact")
     */
    public function removeContact(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $constact = $em->getRepository(ClusterContact::class)->find($id);

        $em->remove($constact);
        $em->flush();


        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @Route("/cluster-contacts/{id}", name="app_inspector_view_contacts")
     */
    public function viewContacts(Request $request, EntityManagerInterface $em, int $id): Response
    {

        $user = $em->getRepository(User::class)->find($id);

        return $this->render('inspector_contacts/view_contacts.html.twig', [
            'controller_name' => 'InspectorController',
            'user' => $user

        ]);
    }


    /**
     * @Route("/add-contact/{id}/", name="app_inspector_contact_add")
     * @Route("/edit-contact/{id}/{contact_id}/", name="app_inspector_contact_edit")
     */
    public function add(Request $request, EntityManagerInterface $em, int $id,  int $contact_id = null): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if($contact_id)
        {
            $contact = $em->getRepository(ClusterContact::class)->find($contact_id);
        }
        else
        {
            $contact = new ClusterContact();
        }



        $form = $this->createFormBuilder($contact)
            ->add('phoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'data-mask' => '+0 (000) 000-00-00',
                    'placeholder' => '+_ (___) ___-__-__',
                ],
                'label' => 'Номер телефона'
            ])->add('addPhoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Добавочный номер',
                'required' => false
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'ФИО'
            ])
            ->add('post', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Должность'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $contact->setUser($user);

            $em->persist($contact);
            $em->persist($user);

            $em->flush();


            return $this->redirectToRoute('app_inspector_view_contacts', ['id'=>$user->getId()]);
        }


        return $this->render('inspector_contacts/add_contact.html.twig', [
            'controller_name' => 'InspectorController',
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


}
