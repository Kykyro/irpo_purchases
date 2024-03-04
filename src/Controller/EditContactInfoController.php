<?php

namespace App\Controller;

use App\Entity\ContactInfo;
use App\Entity\EmployersContact;
use App\Entity\ResponsibleContactType;
use App\Entity\UserInfo;
use App\Form\contactInfoAddEditForm;
use App\Form\contactInfoEditForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditContactInfoController extends AbstractController
{
    /**
     * @Route("/edit-contact-info/{id}", name="app_edit_contact_info")
     */
    public function index(EntityManagerInterface $em, Request $request, $id): Response
    {

        $user = $this->getUser();
        $roles = $user->getRoles();

        if($user->getId() != $id and !(in_array('ROLE_SMALL_CURATOR', $roles) or in_array('ROLE_INSPECTOR', $roles)))
            $id = $user->getId();
        $userInfo = $em->getRepository(UserInfo::class)->find($id);
        $contactInfo = $userInfo->getContactInfo();
        if(is_null($contactInfo))
        {
            $contactInfo = new ContactInfo($userInfo);
//            $contactInfo->setUserInfo($userInfo);
        }
        $contact = [];
        foreach ($contactInfo->getEmployersContacts() as $employersContact)
        {
            if(!$userInfo->getEmployers()->contains($employersContact->getEmployer()))
            {
                $contactInfo->removeEmployersContact($employersContact);
                $em->remove($employersContact);
            }
            else
            {
                array_push($contact, $employersContact->getEmployer()->getId());
            }
        }
        foreach($userInfo->getEmployers() as $employer)
        {
            if(!in_array($employer->getId(), $contact))
            {
                $empCont = new EmployersContact();
                $empCont->setEmployer($employer);
                $contactInfo->addEmployersContact($empCont);
            }
        }
        $form = $this->createForm(contactInfoEditForm::class, $contactInfo);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            foreach ($contactInfo->getResponsibleContacts() as $contact)
            {
                if($contact->getFIO() == "")
                {
                    if(!is_null($contact->getId()))
                    {
                        $contactInfo->removeResponsibleContact($contact);
                        $em->remove($contact);
                    }
                }
                else
                {
                    foreach ($contact->getResponsibleContactTypes() as $contactType)
                    {

                        $contactType->addResponsibleContact($contact);
                        $em->persist($contactType);

                    }


//                    dd($contact);
                    $contact->setContactInfo($contactInfo);
                    $em->persist($contact);

                }

            }
            $em->persist($userInfo);
            $em->persist($contactInfo);
//            dd($contactInfo);
            $em->flush();
            if((in_array('ROLE_SMALL_CURATOR', $roles) or in_array('ROLE_INSPECTOR', $roles)))
            {
                return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $id]);
            }
            else
            {
                return $this->redirectToRoute('app_profile', ['id' => $id]);
            }


        }

        return $this->render('edit_contact_info/index.html.twig', [
            'controller_name' => 'EditContactInfoController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/edit-contact-info-add/{id}", name="app_edit_contact_info_add")
     */
    public function indexAdd(EntityManagerInterface $em, Request $request, $id): Response
    {

        $user = $this->getUser();
        $roles = $user->getRoles();

        if($user->getId() != $id and !(in_array('ROLE_SMALL_CURATOR', $roles) or in_array('ROLE_INSPECTOR', $roles)))
            $id = $user->getId();
        $userInfo = $em->getRepository(UserInfo::class)->find($id);
        $contactInfo = $userInfo->getContactInfo();
        if(is_null($contactInfo))
        {
            $contactInfo = new ContactInfo($userInfo);
//            $contactInfo->setUserInfo($userInfo);
        }
        $contact = [];
        foreach ($contactInfo->getEmployersContacts() as $employersContact)
        {
            if(!$userInfo->getEmployers()->contains($employersContact->getEmployer()))
            {
                $contactInfo->removeEmployersContact($employersContact);
                $em->remove($employersContact);
            }
            else
            {
                array_push($contact, $employersContact->getEmployer()->getId());
            }
        }
        foreach($userInfo->getEmployers() as $employer)
        {
            if(!in_array($employer->getId(), $contact))
            {
                $empCont = new EmployersContact();
                $empCont->setEmployer($employer);
                $contactInfo->addEmployersContact($empCont);
            }
        }
        $form = $this->createForm(contactInfoAddEditForm::class, $contactInfo);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            foreach ($contactInfo->getResponsibleContacts() as $contact)
            {
                if($contact->getFIO() == "")
                {
                    if(!is_null($contact->getId()))
                    {
                        $contactInfo->removeResponsibleContact($contact);
                        $em->remove($contact);
                    }
                }
                else
                {
                    foreach ($contact->getResponsibleContactTypes() as $contactType)
                    {

                        $contactType->addResponsibleContact($contact);
                        $em->persist($contactType);

                    }


//                    dd($contact);
                    $contact->setContactInfo($contactInfo);
                    $em->persist($contact);

                }

            }
            foreach ($contactInfo->getAddContacts() as $contact)
            {
                if($contact->getFIO() == "")
                {
                    if(!is_null($contact->getId()))
                    {
                        $contactInfo->removeAddContact($contact);
                        $em->remove($contact);
                    }
                }
                else
                {



//                    dd($contact);
                    $contact->setContactInfo($contactInfo);
                    $em->persist($contact);

                }

            }
            $em->persist($userInfo);
            $em->persist($contactInfo);
//            dd($contactInfo);
            $em->flush();
            if((in_array('ROLE_SMALL_CURATOR', $roles) or in_array('ROLE_INSPECTOR', $roles)))
            {
                return $this->redirectToRoute('app_inspector_show_info_about_cluster', ['id' => $id]);
            }
            else
            {
                return $this->redirectToRoute('app_profile', ['id' => $id]);
            }


        }

        return $this->render('edit_contact_info/index_add.html.twig', [
            'controller_name' => 'EditContactInfoController',
            'form' => $form->createView(),
        ]);
    }
}
