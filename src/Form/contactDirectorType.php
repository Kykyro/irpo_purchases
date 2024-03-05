<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\CluterDirector;
use App\Entity\ZoneInfrastructureSheet;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class contactDirectorType  extends AbstractType
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
            ->add('post', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => '
                           Должность
                            '
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
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => '
                           Почта
                            '
            ])

//            ->add('photo', FileType::class, [
//                'attr' => [
//                    'class' => 'form-control',
//                    'accept' => "image/*"
//                ],
//
//                'required'   => false,
//                'mapped' => false,
//                'label' => '
//                          Фотография
//                            '
//            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CluterDirector::class,
        ]);
    }
}