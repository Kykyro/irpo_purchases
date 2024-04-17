<?php

namespace App\Controller\Region;

use App\Entity\CertificateFunds;
use App\Entity\CofinancingFunds;
use App\Entity\CofinancingScenario;
use App\Entity\CofinancingScenarioFile;
use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\PurchaseNote;
use App\Entity\RfSubject;
use App\Form\ChoiceInputType;
use App\Form\formWithDate;
use App\Form\purchasesFormType;
use App\Services\certificateOfContractingService;
use App\Services\FileService;
use App\Services\XlsxService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\String\Slugger\SluggerInterface;



/**
 * @Route("/region")
 */
class CofinancingController extends AbstractController
{


    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/co-financing", name="app_region_cofinancing")
     */
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator) : Response
    {
        $user = $this->getUser();
        $funds = $user->getCofinancingFunds();
        if(is_null($funds))
            $funds = new CofinancingFunds($user);

        $query = $em->getRepository(CofinancingScenario::class)
            ->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->orderBy('c.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('co_financing_funds/region/index.html.twig', [
            'controller_name' => 'RegionController',
            'user' => $user,
            'cofinancing_scenarion' => $pagination,
            'funds' => $funds,

        ]);
    }
    /**
     * @Route("/co-financing-add", name="app_region_cofinancing_add")
     * @Route("/co-financing-edit/{id}", name="app_region_cofinancing_edit")
     */
    public function addScenarion(Request $request, EntityManagerInterface $em, int $id=null, FileService $fileService) : Response
    {
        $user = $this->getUser();
        if($id)
        {
            $cofinancingScenario = $em->getRepository(CofinancingScenario::class)->find($id);
        }
        else
        {
            $cofinancingScenario = new CofinancingScenario($user);
        }


        $form = $this->createFormBuilder($cofinancingScenario)
            ->add('files', FileType::class, [
                'multiple' => true,
                'attr'     => [
                    'class' => 'form-control',
                    'multiple' => 'multiple'
                ],
                'label' => 'Файлы',
                'mapped' => false,
                'required' => false,
            ])
            ->add('scenario', ChoiceType::class, [
                'attr'     => [
                    'class' => 'form-control',

                ],
                'choices'  => [
                    'Передача имущества (оборудование, мебель, ПО, расходные материалы и т.д.)' => 'Передача имущества (оборудование, мебель, ПО, расходные материалы и т.д.)',
                    'Передача помещений/зданий' => 'Передача помещений/зданий',
                    'Передача денежных средств' => 'Передача денежных средств',
                    'Проведение ремонтных работ' => 'Проведение ремонтных работ',
                    'Иное' => 'Иное',
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => 'Категория',
            ])
            ->add('comment', TextareaType::class, [
                'attr'     => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Комментарий',
            ])
            ->add('anotherScenario', TextType::class, [
                'attr'     => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Иное',
            ])
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid())
        {
            $files = $form->get('files')->getData();

            foreach ($files as $file)
            {
                $scenarioFile = new CofinancingScenarioFile();

                $scenarioFile->setFile($fileService->UploadFile($file, 'cofinancing_file_directory'));

                $cofinancingScenario->addFile($scenarioFile);

                $em->persist($scenarioFile);
            }
            $cofinancingScenario->setStatus('На проверке');

            $em->persist($cofinancingScenario);

            $em->flush();

            return $this->redirectToRoute('app_region_cofinancing');
        }

        return $this->render('co_financing_funds/region/addScenario.html.twig', [
            'controller_name' => 'RegionController',
            'user' => $user,
            'cofinancing_scenarion' => $cofinancingScenario,
            'form' => $form->createView(),

        ]);
    }





}
