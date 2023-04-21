<?php

namespace App\Controller\Admin;

use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Form\RegistrationUserInfoFormType;
use App\Form\UserEditFormType;
use App\Form\RegistrationFormType;
use App\Form\UserPasswordEditFormType;
use App\Security\LoginFormAuthenticator;
use App\Services\XlsxEdAndEmplListService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use function Sodium\add;
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
 * @Route("/inspector")
 *
 */
class AdminOrgListController extends AbstractController
{
    /**
     * @Route("/org-list", name="app_admin_org_list")
     */
    public function adminPanel(): Response
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $clusters = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('uf.year > :year')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%ROLE_REGION%")
            ->setParameter('year', 2021)
            ->orderBy('uf.year', 'ASC')
            ->getQuery()
            ->getResult();
//        dd($clusters);
        $arr = [];
        if(count($clusters) > 0)
        {
            foreach ($clusters as $cluster)
            {
                if(!array_key_exists($cluster->getUserInfo()->getYear(), $arr))
                {
                   $arr[$cluster->getUserInfo()->getYear()] = [];
                }

                array_push($arr[$cluster->getUserInfo()->getYear()], $cluster->getUserInfo());
            }
        }

        return $this->render('admin/templates/orgList.html.twig', [
            'controller_name' => 'AdminController',
            'clusters_info' => $arr,
        ]);
    }

    /**
     * @Route("/org-list-download", name="app_admin_org_list_download")
     */
    public function orgListDownload(XlsxEdAndEmplListService $andEmplListService): Response
    {


        return $andEmplListService->generate();
    }

}
