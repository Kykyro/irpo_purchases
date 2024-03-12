<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form;
use App\Entity\ZoneInfrastructureSheet;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class infrastructureSheetRegionEditType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('factNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '1',
                    'min' => '0',
                ],
                'label' => false,
                'required' => false,
                'empty_data' => 0,
            ])
            ->add('deliveryDate', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => false,
                'required' => false,
                'widget' => 'single_text',
            ])


            ->add('OKPD2', TextType::class, [
                'attr' => [
                    'class' => 'form-control okpd2',
                    'placeholder' => '_ _ . _ _ . _ _ . _ _ _',
                    'data-mask' => '00.00.00.000'
                ],

                'label' => false,
                'required' => false,
                'empty_data' => "",
            ])
            ->add('KTRU', TextType::class, [
                'attr' => [
                    'class' => 'form-control ktru',
                    'placeholder' => '_ _ . _ _ . _ _ . _ _ _ - _ _ _ _ _ _ ',
                    'data-mask' => '00.00.00.000-000000'
                ],
                'label' => false,
                'required' => false,
                'empty_data' => "",
            ])
            ->add('countryOfOrigin', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'label' => false,
                'required' => false,
                'empty_data' => "",
            ])
            ->add('model', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => false,
                'required' => false,
                'empty_data' => "",
            ])


        ;
        if($options['is_bas'])
        {
            $builder
            ->add('inventoryNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => false,
                'required' => false,
                'empty_data' => "",
//                'mapped' => false,
            ])
            ->add('sum', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => false,
                'required' => false,
                'empty_data' => 0,
//                'mapped' => false,
            ])

            ;
        }
        else
        {
            $builder
                ->add('putIntoOperation', TextType::class, [
                    'attr' => [
                        'class' => 'form-control',
                        'step' => '1',
                        'min' => '0',
                    ],
                    'label' => false,
                    'required' => false,
                    'empty_data' => 0,
                ])
                ->add('comment', TextareaType::class, [
                    'attr' => [
                        'class' => 'form-control',

                    ],
                    'label' => false,
                    'required' => false,
                    'empty_data' => '',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ZoneInfrastructureSheet::class,
            'is_bas' => false
        ]);
    }
}