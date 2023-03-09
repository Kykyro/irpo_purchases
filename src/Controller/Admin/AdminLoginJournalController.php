<?php

namespace App\Controller\Admin;

use App\Entity\LoginJournal;
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
class AdminLoginJournalController extends AbstractController
{
    /**
     * @Route("/cluster-login-journal", name="app_admin_login_journal")
     */
    public function adminPanel(Request $request): Response
    {


        $entity_manager = $this->getDoctrine()->getManager();

        $journal = $entity_manager->getRepository(LoginJournal::class)
            ->createQueryBuilder('a')
//            ->andWhere('a.roles LIKE :role')
//            ->setParameter('role', "%ROLE_REGION%")
            ->getQuery()
            ->getResult();



        return $this->render('admin/templates/loginJournal.html.twig', [
            'controller_name' => 'AdminController',
            'journal' => $journal,
        ]);
    }


}
