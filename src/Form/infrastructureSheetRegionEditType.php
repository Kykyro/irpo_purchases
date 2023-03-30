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
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'label' => false,
                'required' => false,
                'empty_data' => '',
            ])
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
            ->add('OKPD2', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => false,
                'required' => false,
                'empty_data' => "",
            ])
            ->add('KTRU', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ZoneInfrastructureSheet::class,
        ]);
    }
}