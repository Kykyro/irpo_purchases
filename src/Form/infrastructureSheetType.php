<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form;
use App\Entity\ZoneInfrastructureSheet;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class infrastructureSheetType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'label' => false,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('totalNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => false,
                'required' => false,
                'empty_data' => 0,
            ])
            ->add('units', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'label' => false,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('type', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'label' => false,
                'required' => true,
                'empty_data' => '',
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