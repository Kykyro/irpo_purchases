<?php

namespace App\Controller\Admin;

use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\UserEditFormType;
use App\Form\RegistrationClusterFormType;
use App\Form\UserPasswordEditFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 *
 * @Route("/admin")
 *
 */
class AdminAccessController extends AbstractController
{
    /**
     * @Route("/cluster-access-to-purchases", name="app_admin_cluster_access")
     */
    public function adminPanel(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {


        $entity_manager = $this->getDoctrine()->getManager();

        $clusters = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%ROLE_REGION%")
            ->getQuery()
            ->getResult();



        return $this->render('admin/templates/accessToPurchases.html.twig', [
            'controller_name' => 'AdminController',
            'clusters' => $clusters,
        ]);
    }

    /**
     * @Route("/cluster-access-to-purchases-change/{id}", name="app_admin_cluster_access_change")
     */
    public function changeAccess(int $id){

        $entity_manager = $this->getDoctrine()->getManager();
        $userInfo = $entity_manager->getRepository(UserInfo::class)->find($id);

        if(is_null($userInfo->isAccessToPurchases() )){
           $userInfo->setAccessToPurchases(true);
        }


        $userInfo->setAccessToPurchases(!$userInfo->isAccessToPurchases());


        $entity_manager->persist($userInfo);
        $entity_manager->flush();

        return $this->redirectToRoute('app_admin_cluster_access');
    }

    /**
     * @Route("/cluster-access-to-purchases-change-all/{access}", name="app_admin_cluster_access_change_all")
     */
    public function changeAccessToAll(bool $access){
//dd($access);
        $entity_manager = $this->getDoctrine()->getManager();
        $userInfo = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%ROLE_REGION%")
            ->getQuery()
            ->getResult();


        foreach ($userInfo as $item){
            $item->getUserInfo()->setAccessToPurchases($access);
            $entity_manager->persist($item);
        }


        $entity_manager->flush();

        return $this->redirectToRoute('app_admin_cluster_access');
    }

}
