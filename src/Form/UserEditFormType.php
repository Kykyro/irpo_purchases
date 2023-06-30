<?php

namespace App\Form;

use App\Entity\User;
//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

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
                            'Журналист' => array
                            (
                                'Журналист' => 'ROLE_JOURNALIST'
                            ),
                            'РОИВ' => array
                            (
                                'РОИВ' => 'ROLE_ROIV'
                            )
                        )
                    ,
                    'multiple' => true,
                    'required' => true,
                    'expanded' => true,
                ]
            )
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'label' => 'Имя'
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
