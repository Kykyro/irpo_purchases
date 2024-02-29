<?php

namespace App\Controller\BasCurator;

use App\Entity\User;
use App\Form\Users\BasEditFormType;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasUserEditController extends AbstractController
{
    /**
     * @Route("/bas-curator/user-edit/{id}", name="app_bas_user_edit")
     */
    public function index(EntityManagerInterface $em, Request $request, $id, FileService $fileService): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        $user_info = $user->getUserInfo();

        $form = $this->createForm(BasEditFormType::class, $user_info);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {

            $photo = $form->get('_photo')->getData();

            if($photo)
                $user_info->setPhoto($fileService->UploadFile($photo, 'cluster_photo_directory'));

            $arr = [];
            $key = 1;
            $orgList = $user_info->getListOfEdicationOrganization();
            foreach (array_keys($orgList) as $i)
            {
                $arr[$key] = $orgList[$i];
                $key++;
            }
            $user_info->setListOfEdicationOrganization($arr);

            $em->persist($user_info);
            $em->flush();
            return $this->redirectToRoute('app_bas_curator_info', ['id' => $user->getId()]);
        }


        return $this->render('bas_user_edit/editUserInfo.html.twig', [
            'controller_name' => 'BasUserEditController',
            'userInfoForm' => $form->createView(),

        ]);
    }
}
