<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationClusterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uuid')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' =>
                        array
                        (

                            'Производственный кластер' => array
                            (
                                'Производственный кластер' => 'ROLE_REGION'

                            ),
                            'Малый кластерLOT_' => [
                                'Малый кластер' => 'ROLE_SMALL_CLUSTERS'
                            ],
                            'Малый кластер лот 1' => [
                                'Малый кластер лот 1' => 'ROLE_SMALL_CLUSTERS_LOT_1'
                            ],
                            'Малый кластер лот 2' => [
                                'Малый кластер лот 2' => 'ROLE_SMALL_CLUSTERS_LOT_2'
                            ]
                        )
                    ,
                    'multiple' => true,
                    'required' => true,
                    'label' => 'Роль',
                     'expanded' => true,
                ]
            )
            ->add('userInfo', RegistrationUserInfoFormType::class)
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success btn-lg'
                ],
                'label' => 'Создать'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
