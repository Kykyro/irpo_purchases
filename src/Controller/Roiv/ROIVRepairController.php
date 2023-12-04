<?php

namespace App\Controller\Roiv;

use App\Entity\Building;
use App\Entity\ProfEduOrg;
use App\Entity\User;
use App\Form\editRoivBuildingForm;
use App\Services\XlsxRepairNeededService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use function PHPUnit\Framework\throwException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//analyst
/**
 * @Route("/roiv")
 */
class ROIVRepairController extends AbstractController
{
    /**
     * @Route("/list-repair", name="app_roiv")
     */
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $query = $em->getRepository(ProfEduOrg::class)
            ->createQueryBuilder('p')
            ->andWhere('p.region = :id')
            ->setParameter('id', $user->getUserInfo()->getRfSubject()->getId())

        ;

        $form = $this->createFormBuilder()
            ->add('search', TextType::class, [
                'attr' => [
                    'class' => 'form-control col-sm-12 search-bar',
                    'autocomplete' => 'off'
                ],
                'label' => 'Тег'
            ])
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $search = $form->getData()['search'];

            $query = $query
                ->andWhere('p.fullName LIKE :fullName')
                ->setParameter('fullName', "%$search%");
        }


        $query = $query->orderBy('p.id', 'ASC')->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('roiv/index.html.twig', [
            'controller_name' => 'ROIVListController',
            'orgs' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/repair-download", name="app_roiv_download_xlsx")
     */
    public function downloadXlsx(Request $request, XlsxRepairNeededService $service)
    {
        $submittedToken = $request->request->get('token');
        $year = $request->request->get('year');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('download_xlsx', $submittedToken)) {
            return $service->generate($year, $user);
        }

        throw new Exception('Ошибка');
    }

    /**
     * @Route("/prof-edu-org/{id}", name="app_roiv_view_edu")
     */
    public function viewProfEduOrg(int $id, EntityManagerInterface $em)
    {
        $org = $em->getRepository(ProfEduOrg::class)->find($id);
        $user = $this->getUser();


        if($org->getRegion() != $user->getUserInfo()->getRfSubject())
        {
            return $this->denyAccessUnlessGranted('', '', '');
        }

        return $this->render('roiv/prof_edu_org.html.twig', [
            'org' => $org,
        ]);

    }


    /**
     * @Route("/add-buildings/{id}", name="app_roiv_add_buildings")
     */
    public function addBuildings(Request $request, int $id, EntityManagerInterface $em)
    {
        $edu = $em->getRepository(ProfEduOrg::class)->find($id);
        $building = new Building();

        $form = $this->createForm(editRoivBuildingForm::class, $building);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $building->setOrganization($edu);
            $em->persist($building);
            $em->flush();
            return $this->redirectToRoute('app_roiv_view_edu', ['id' => $edu->getId()]);
        }

        return $this->render('roiv/add_buildings.html.twig', [
            'form' => $form->createView(),
            'title' => 'Создания здания',
            'edu_id' => $edu->getId(),
        ]);
    }


    /**
     * @Route("/edit-buildings/{id}", name="app_roiv_edit_buildings")
     */
    public function editBuildings(Request $request, int $id, EntityManagerInterface $em)
    {
        $building = $em->getRepository(Building::class)->find($id);


        $form = $this->createForm(editRoivBuildingForm::class, $building);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($building);
            $em->flush();
            return $this->redirectToRoute('app_roiv_view_edu', ['id' => $building->getOrganization()->getId()]);
        }

        return $this->render('roiv/add_buildings.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование здания',
            'edu_id' => $building->getOrganization()->getId(),

        ]);
    }

    /**
     * @Route("/delete-buildings/{id_org}/{id}", name="app_roiv_delete_buildings")
     */
    public function deleteBuilding(Request $request, int $id, int $id_org, EntityManagerInterface $em)
    {
        $org = $em->getRepository(ProfEduOrg::class)->find($id_org);
        $user = $this->getUser();
        if($org->getRegion() != $user->getUserInfo()->getRfSubject())
        {
            return $this->denyAccessUnlessGranted('', '', '');
        }

        $building = $em->getRepository(Building::class)->find($id);

        $em->remove($building);
        $em->flush();

        return $this->redirectToRoute("app_roiv_view_edu", ['id' => $org->getId()]);

    }

    /**
     * @Route("/delete-organization//{id}", name="app_roiv_delete_organization")
     */
    public function deleteOrganization(Request $request, int $id, EntityManagerInterface $em)
    {
        $org = $em->getRepository(ProfEduOrg::class)->find($id);
        $user = $this->getUser();
        if($org->getRegion() != $user->getUserInfo()->getRfSubject())
        {
            return $this->denyAccessUnlessGranted('', '', '');
        }

        foreach ($org->getBuildings() as $building)
            $em->remove($building);

        $em->remove($org);
        $em->flush();

        return $this->redirectToRoute("app_roiv_view_edu", ['id' => $org->getId()]);

    }

}
