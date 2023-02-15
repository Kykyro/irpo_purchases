<?php

namespace App\Form;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationUserInfoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("rf_subject", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'class' => RfSubject::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Субъект РФ'
            ])
            ->add("organization", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Наименование организации грантополучателя '
                ])
            ->add("educational_organization", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Наименование базовой образовательной организации'
                ])
            ->add("cluster", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Наименование кластера '
                ])
            ->add("declared_industry", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Заявленная отрасль'
                ])
            ->add('year', ChoiceType::class, [
                'choices'  => [

                    '2021' => 2021,
                    '2023' => 2023,
                    '2024' => 2024,

                ],
                'required'   => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Год создания кластера'
            ])
            ->add('studentsCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта "Профессионалитет", разработанных в том числе с применением автоматизированных методов конструирования указанных образовательных программ'
            ])
            ->add('programCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                            Количество реализуемых образовательных программ в интересах организации реального сектора экономики
                            '
            ])
            ->add('teacherCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                            Количество педагогических работников, владеющих актуальными педагогическими, производственными (профильными), цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики
                            '
            ])
            ->add('workerCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                          Количество работников организаций реального сектора экономики, владеющих актуальными педагогическими навыками, цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения по совместительству
                            '
            ])
            ->add('studentsCountWithMentor', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                          Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта "Профессионалитет", разработанных, в том числе, с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе образовательно- производственного центра с закреплением наставника, работающего в организации реального сектора экономики
                            '
            ])
            ->add('jobSecurityCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                          Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта "Профессионалитет", разработанных в том числе с применением автоматизированных методов конструирования указанных образовательных программ (единиц)
                            '
            ])
            ->add('amountOfFunding', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                          Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками центра, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания центра
                            '
            ])
            ->add('amountOfExtraFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => '
                          Объем внебюджетных средств (включая стоимость безвозмездно переданного образовательным организациям, являющимся участниками центра, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых участниками центра из числа организаций, действующих в реальном секторе экономики, на развитие центра                             
                            '
            ])
            ->add('listOfEdicationOrganization', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype' => true,
                'delete_empty' => true,
                'prototype_data' => '',
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('listOfEmployers', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype' => true,
                'delete_empty' => true,
                'prototype_data' => '',
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('extraFundsOO', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => 'Внебюджетные средства Образовательной организации'
            ])
            ->add('extraFundsEconomicSector', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => 'Объем внебюджетных средств организаций реального сектора экономики'
            ])
            ->add('financingFundsOfSubject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => true,
                'label' => 'Объём финансирования из средств  субъекта РФ'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
        ]);
    }
}
