<?php

namespace App\Controller;

use App\Entity\CompitenceProfile;
use App\Entity\User;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompitenceProfileController extends AbstractController
{
    /**
     * @Route("/user/compitence-profile/upload/{id}", name="app_compitence_profile_upload")
     */
    public function index(Request $request, EntityManagerInterface $em, int $id, FileService $fileService): Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('upload-compitence-profile', $submittedToken))
        {

            $user = $em->getRepository(User::class)->find($id);
            $compitenceProfile = $user->getCompitenceProfiles()->last();
            if($compitenceProfile)
            {
                $compitenceProfile->setCreatedAt(new \DateTimeImmutable('now'));
                $compitenceProfile->setStatus('На проверке');
                $file = $request->files->get('file');
                if($file)
                {
                    $fileService->DeleteFile($compitenceProfile->getFile(), 'compitence_profile_directory');
                    $compitenceProfile->setFile($fileService->UploadFile($file, 'compitence_profile_directory'));
                }
            }
            else
            {
                $compitenceProfile = new CompitenceProfile();
                $compitenceProfile->setCreatedAt(new \DateTimeImmutable('now'));
                $compitenceProfile->setStatus('На проверке');
                $compitenceProfile->setUser($user);
                $file = $request->files->get('file');
                if($file)
                {
                    $compitenceProfile->setFile($fileService->UploadFile($file, 'compitence_profile_directory'));
                }


            }

            $em->persist($user);
            $em->persist($compitenceProfile);
            $em->flush();
        }

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @Route("/user/compitence-profile/check/{id}", name="app_compitence_profile_check")
     */
    public function compitenceProfileCheck(Request $request, EntityManagerInterface $em, int $id, FileService $fileService): Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('upload-compitence-profile-check', $submittedToken))
        {

            $user = $em->getRepository(User::class)->find($id);
            $compitenceProfile = $user->getCompitenceProfiles()->last();
            if($compitenceProfile)
            {
                $status = $request->request->get('status');
                $comment = $request->request->get('comment');
                $compitenceProfile->setStatus($status);
                $compitenceProfile->setComment($comment);

            }
            else
            {
               throw new \Exception('Ошибка. Отсутствует инфрмация');
            }

            $em->persist($compitenceProfile);
            $em->flush();
        }

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
