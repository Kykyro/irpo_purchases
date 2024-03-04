<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form;
use App\Entity\AddContacts;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\CluterDirector;
use App\Entity\EmployersContact;
use App\Entity\ResponsibleContact;
use App\Entity\ZoneInfrastructureSheet;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class contactAddType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('FIO', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => 'ФИО'
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],

                'required'   => false,
                'label' => '
                      Мобильный телефон
                            '
            ])
            ->add('post', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => '
                           Должность
                            '
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => '
                           Почта
                            '
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'РОИВ' => 'РОИВ',
                    'Дополнительный контакт' => 'Дополнительный контакт',
                    'Контакт из заявки' => 'Контакт из заявки',

                ],
                'multiple' => false,
                'expanded' => false,
                'label' => '',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddContacts::class,
        ]);
    }
}