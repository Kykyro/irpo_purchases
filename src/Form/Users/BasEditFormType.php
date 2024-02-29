<?php

namespace App\Form\Users;

use App\Entity\Employers;
use App\Entity\RfSubject;
use App\Entity\User;
//use Doctrine\DBAL\Types\TextType;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BasEditFormType extends AbstractType
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


            ->add("initiatorOfCreation", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Инициатор создания кластера '
                ])
            ->add("curator", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'label' => 'Куратор ',

                ])



            ->add('year', ChoiceType::class, [
                'choices'  => [

                    '2021' => 2021,
                    '2022' => 2022,
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



            ->add('listOfEdicationOrganization', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype' => true,
                'delete_empty' => true,
                'prototype_data' => '',
                'allow_delete' => true,
                'by_reference' => false,
            ])


            ->add('_photo', FileType::class, [
                'attr' => [
                    'class' => 'form-control col-lg-12'
                ],
                'mapped' => false,
                'label' => 'Фотография',

                'required'   => false,
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
