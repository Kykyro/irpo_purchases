<?php

namespace App\Controller\Admin;

use App\Entity\Employers;
use App\Entity\EmployersCategory;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\RegistrationUserInfoFormType;
use App\Form\UserEditFormType;
use App\Form\RegistrationFormType;
use App\Form\UserPasswordEditFormType;
use App\Security\LoginFormAuthenticator;
use App\Services\XlsxEmployersService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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



class AdminEmployersController extends AbstractController
{



    /**
     *
     * @Route("/analyst/employers", name="app_analyst_employers")
     *
     */
    public function employers(Request $request, EntityManagerInterface $em,  PaginatorInterface $paginator)
    {
        $arr = [];

        $query = $em->getRepository(Employers::class)
            ->createQueryBuilder('e')
            ->leftJoin('e.employersCategories', 'cat')
            ->leftJoin('e.userInfos', 'uf')
            ->andWhere('uf.year > :year')
            ->setParameter('year', 2021)
            ->orderBy('e.name', 'ASC')
        ;

        $form = $this->createFormBuilder($arr)
            ->add('search', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'label' => 'Поиск'
            ])
            ->add('employersCategories', EntityType::class, array(
                'class'     => EmployersCategory::class,
                'expanded'  => false,
                'multiple'  => false,
                'by_reference' => false,
                'choice_label' => function ($cat) {
                    return $cat->getName();
                },
                'attr' => [
                    'class' => 'form-control m-b  select2',
                ],
                'required' => false,
                'label' => 'Категория'
            ))
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Поиск'
            ])
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
//            dd($data);
            $search = $data['search'];
            $query = $query
                ->andWhere('e.name LIKE :search')
                ->setParameter('search', "%$search%");
            if($data['employersCategories'])
            {
                $cat = $data['employersCategories'];
                $query = $query
                    ->andWhere('cat.name = :cat')
                    ->setParameter('cat', $cat->getName());
            }
        }

        $query = $query->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('analyst/templates/employers.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/analyst/employers-edit/{id}", name="app_analyst_employer_edit")
     * @Route("/analyst/employers-add", name="app_analyst_employer_add")
     *
     */
    public function editEmployers(Request $request, EntityManagerInterface $em,  int $id=null)
    {
        if($id)
            $empl = $em->getRepository(Employers::class)->find($id);
        else
            $empl = new Employers();

        $form = $this->createFormBuilder($empl)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Название'
            ])
            ->add('altName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Название (альтернативно)',
                'required' => false,
            ])
            ->add('shortName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Сокращенное название',
                'required' => false,
            ])
            ->add('inn', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'ИНН',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Город',
                'required' => false,
            ])
            ->add('region', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Область',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Описание',
                'required' => false
            ])
            ->add('OKVD', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'ОКВЭД',
                'required' => false
            ])
            ->add('OKVDadd', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'ОКВЭД (дополнительный)',
                'required' => false
            ])
            ->add('userInfos', EntityType::class, array(
                'class'     => UserInfo::class,
                'expanded'  => false,
                'multiple'  => true,
                'by_reference' => false,
                'choice_label' => function ($uf) {
                    return $uf->getEducationalOrganization();
                },
                'attr' => [
                    'class' => 'form-control m-b  select2',
                ],
                'required' => false,
                'label' => 'Кластеры'

            ))
            ->add('employersCategories', EntityType::class, array(
                'class'     => EmployersCategory::class,
                'expanded'  => false,
                'multiple'  => true,
                'by_reference' => false,
                'choice_label' => function ($cat) {
                    return $cat->getName();
                },
                'attr' => [
                    'class' => 'form-control m-b  select2',
                ],
                'required' => false,
                'label' => 'Категория'
            ))
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $data = $form->getData();
            foreach ($data->getUserInfos() as $info)
                $empl->addUserInfo($info);
            $em->persist($empl);
//            dd($empl);
            $em->flush();
            return $this->redirectToRoute('app_analyst_employers');
        }

        return $this->render('analyst/templates/employerEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/analyst/employers-category-edit/{id}", name="app_analyst_employer_category_edit")
     * @Route("/analyst/employers-category-add", name="app_analyst_employer_category_add")
     *
     */
    public function editEmployersCategory(Request $request, EntityManagerInterface $em,  int $id=null)
    {
        if($id)
            $emplCat = $em->getRepository(EmployersCategory::class)->find($id);
        else
            $emplCat = new EmployersCategory();

        $form = $this->createFormBuilder($emplCat)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Название'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Сохранить'
            ])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($emplCat);
            $em->flush();
            return $this->redirectToRoute('app_analyst_employers');
        }

        return $this->render('analyst/templates/employerCategoryEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/analyst/employers-download-table", name="app_analyst_employer_table_download")
     */
    public function downloadTable(XlsxEmployersService $employersService)
    {
        return $employersService->generate();
    }

}
