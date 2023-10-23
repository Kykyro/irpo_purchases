<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
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
                            'Админ' => array
                            (
                                'админ' => 'ROLE_ADMIN'
                            ),
                            'супер админ' => array
                            (
                                'супер админ' => 'ROLE_SUPERADMIN'
                            ),
                            'Пользователь' => array
                            (
                                'регион' => 'ROLE_REGION'
                            ),
                            'проверяющий' => array
                            (
                                'проверяющий' => 'ROLE_INSPECTOR'
                            ),
                            'наблюдатель' => array
                            (
                                'наблюдатель' => 'ROLE_SPECTATOR'
                            ),
                            'Маленький кластер' => array
                            (
                                'Маленький кластер' => 'ROLE_SMALL_CLUSTERS'
                            ),
                            'Куратор маленьких кластеров' => array
                            (
                                'Куратор маленьких кластеров' => 'ROLE_SMALL_CURATOR'
                            ),
                            'Аналитик' => array
                            (
                                'Аналитик' => 'ROLE_ANALYTIC'
                            ),
                            'РОИВ' => array
                            (
                                'РОИВ' => 'ROLE_ROIV'
                            ),
                            'Дисциплина' => array
                            (
                                'Дисциплина' => 'ROLE_DISCIPLINE'
                            ),
                            'Дирекция' => array
                            (
                                'Дирекция' => 'ROLE_DIRECTORATE'
                            )
                        )
                    ,
                    'multiple' => true,
                    'required' => true,
                    'expanded' => true,

                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
