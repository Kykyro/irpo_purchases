<?php

namespace App\Controller\SmallCurator;

use App\Entity\FavoritesClusters;
use App\Entity\InfrastructureSheetRegionFile;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\InspectorPurchasesFindFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcurementProcedures;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\XlsxService;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class InspectorController
 * @package App\Controller
 * @Route("/inspector")
 */
class SmallCuratorOrgAndEmplController extends AbstractController
{
    /**
     * @Route("/small-clusters/org-and-empl", name="app_sc_org_and_empl", methods="GET|POST")
     */
    public function regionUserList(Request $request,EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {

        $entity_manager = $this->getDoctrine()->getManager();
        $clusters = $entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('uf.year > :year')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%ROLE_SMALL_CLUSTERS%")
            ->setParameter('year', 2021)
            ->orderBy('uf.year', 'ASC')
            ->getQuery()
            ->getResult();

        $arr = [];
        if(count($clusters) > 0)
        {
            foreach ($clusters as $cluster)
            {

                if(in_array('ROLE_SMALL_CLUSTERS_LOT_2', $cluster->getRoles()))
                {
                    $key = $cluster->getUserInfo()->getYear()." Лот 2";
                }
                else if(in_array('ROLE_SMALL_CLUSTERS_LOT_1', $cluster->getRoles()))
                {
                    $key = $cluster->getUserInfo()->getYear()." Лот 1";
                }
                else{
                    $key = $cluster->getUserInfo()->getYear();
                }

                if(!array_key_exists($key, $arr))
                {
                    $arr[$key] = [];
                }

                array_push($arr[$key], $cluster->getUserInfo());
            }
        }


        return $this->render('admin/templates/orgList.html.twig', [
            'controller_name' => 'InspectorController',
            'clusters_info' => $arr,
        ]);
    }



}
