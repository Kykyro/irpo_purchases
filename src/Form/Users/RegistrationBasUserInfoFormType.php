<?php

namespace App\Form\Users;

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

class RegistrationBasUserInfoFormType extends AbstractType
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
                    'required'   => false,
                    'label' => 'Инициатор создания кластера '
                ])

            ->add('year', ChoiceType::class, [
                'choices'  => [

                    '2021' => 2021,
                    '2024' => 2024,

                ],

                'required'   => false,
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

                'required'   => false,
                'label' => 'Количество обучающихся по образовательным программам дополнительного образования и образовательным программам среднего профессионального образования в сфере БАС, в том числе с использованием электронного обучения и (или) дистанционных образовательных технологий (нарастающим итогом)'
            ])
            ->add('programCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                           Количество реализуемых образовательных программ среднего профессионального образования, дополнительных образовательных программ, в которые включены модули по обучению навыкам проектирования, разработки, производства и эксплуатации БАС с использованием цифрового образовательного контента (ЦОК) (накопительным итогом).
                            '
            ])
            ->add('EduOrgsCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                           Количество образовательных организаций, в которых реализуются основные общеобразовательные программы, за исключением образовательных программ дошкольного образования, образовательные программы дополнительного образования и образовательные программы среднего профессионального образования в сфере БАС, в том числе с использованием электронного обучения и (или) дистанционных образовательных технологий (нарастающим итогом).
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

                'required'   => false,
                'label' => '
                            Количество обученных педагогических работников для образовательных организаций, реализующих основные общеобразовательные программы, за исключением образовательных программ человек 38 24 / 207 дошкольного образования, образовательные программы среднего профессионального образования и дополнительные образовательные программы в рамках мероприятий по обучению и подготовке квалифицированных педагогических кадров (накопительным итогом).
                            '
            ])

            ->add('financingFundsOfSubject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.001',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => 'Объём финансирования из средств субъекта РФ'
            ])
            ->add('fedFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.001',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => 'Объём финансирования из федерального бюджета'
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
