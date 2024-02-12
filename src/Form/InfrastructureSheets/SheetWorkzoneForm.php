<?php

namespace App\Form\InfrastructureSheets;

use App\Entity\ZoneRequirements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SheetWorkzoneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ugps = $builder->getData()->getUser()->getUserInfo()->getUGPS();
//        dd(array_values($ugps));
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Наименование зоны:',
            ])
            ->add('FGOS', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'  => $ugps,
                'choice_label' => function ($choice,  $key,  $value) {
                    return $value;
                },

                'multiple' => false,
                'expanded' => false,
                'label' => 'Код и наименование профессии или специальности согласно ФГОС СПО:',
            ])
            ->add('zoneRequirements', zoneRequirementsType::class, [


                    'label' => false,

                ]
            )
        ;
    }


}
