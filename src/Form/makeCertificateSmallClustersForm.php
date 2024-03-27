<?php

namespace App\Form;

use App\Entity\User;

use App\Entity\ZoneType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class makeCertificateSmallClustersForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clusters', EntityType::class, [
//                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'label' => 'Адрес',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin('u.user_info', 'uf')
                        ->leftJoin('uf.rf_subject', 'sub')
                        ->andWhere('u.roles LIKE :role')
                        ->andWhere('uf.year > :year')
                        ->setParameter('role', '%ROLE_SMALL_CLUSTERS%')
                        ->setParameter('year', 2021)
                        ->orderBy('sub.name', 'ASC')
                        ;
                },
                'choice_label' => function ($clusters) {
                    return $clusters->getUserInfo()->getCluster();
                },
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => ChoiceList::attr($this, function (?User $user) {
                    return $user ? [
                        'data-year' => $user->getUserInfo()->getYear(),
                        'data-region' => is_null($user->getUserInfo()->getRfSubject()) ? '' : $user->getUserInfo()->getRfSubject()->getName(),
                        'data-industry' => $user->getUserInfo()->getDeclaredIndustry(),
                        'data-base' => $user->getUserInfo()->getOrganization(),
                        'data-district' => is_null($user->getUserInfo()->getRfSubject()) ? '' : $user->getUserInfo()->getRfSubject()->getDistrict(),
                        'data-zone' => json_encode($user->getUserInfo()->getZone(), JSON_UNESCAPED_UNICODE),
                        'data-ugps' => json_encode($user->getUserInfo()->getUGPS(), JSON_UNESCAPED_UNICODE),
                        'data-city' => json_encode($user->getUserInfo()->getCity(), JSON_UNESCAPED_UNICODE),
                        'data-employers' => json_encode($user->getUserInfo()->getListOfEmployers(), JSON_UNESCAPED_UNICODE),
                        'data-tags' => json_encode($user->getUserTagsArray(), JSON_UNESCAPED_UNICODE),
                    ]
                        : [];
                }),

            ])
            ->add('option', ChoiceType::class, [
                'choices'  => [
                    'Зоны по видам работ' => 'zone',
                    'УГПС' => 'ugps',
                    'Расходы' => 'budget',
                    'Оборудование' => 'equipment',
                    'Ремонт' => 'repair',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class,[

                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Скачать'

            ])

            ->add('download_as_table', CheckboxType::class, [
                'label' => 'Cкачать как таблицу',
                'required' => false,
            ])
            ->add('as_choose', CheckboxType::class, [
                'label' => 'Как в выборке',
                'required' => false,
            ])
            ->add('UGPS', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control m-b select2 select2-ugps-input-results ',
                ],
                'mapped' => false,
                'multiple' => true,
//                'expanded' => false,
                'required' => false,
//                'allow_add' => TRUE,

            ])
            ->add('employers', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control m-b select2 '
                ],
                'mapped' => false,
                'multiple' => true,
//                'expanded' => false,
                'required' => false,
//                'allow_add' => TRUE,

            ])
            ->add('zones', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control m-b select2 '
                ],
                'mapped' => false,
                'multiple' => true,
//                'expanded' => false,
                'required' => false,
//                'allow_add' => TRUE,

            ])


        ;

        $builder->get('UGPS')->resetViewTransformers();

    }
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'validation_groups' => false,
//        ]);
//    }


}

