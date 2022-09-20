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
                            'Проверяющий' => array
                            (
                                'проверяющий' => 'ROLE_INSPECTOR'
                            ),
                            'Журналист' => array
                            (
                                'проверяющий' => 'ROLE_JOURNALIST'
                            )
                        )
                    ,
                    'multiple' => true,
                    'required' => true,
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
