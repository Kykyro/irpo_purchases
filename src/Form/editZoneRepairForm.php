<?php

namespace App\Form;

use App\Entity\User;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class editZoneRepairForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Dismantling', TextType::class, [
                'attr' => [
                    'class' => 'form-control fin',
                    'step' => '1',
                    'min' => '0',
                    'max' => '100'
                ],
                'required'   => true,
                'label' => 'Демонтажные работы',
                'empty_data' => 100,

            ])
            ->add('plasteringAndCommunication', TextType::class, [
                'attr' => [
                    'class' => 'form-control fin',
                    'step' => '1',
                    'min' => '0',
                    'max' => '100'
                ],
                'required'   => true,
                'label' => 'Штукатурные и коммуникационные работы',
                'empty_data' => 100,

            ])
            ->add('finishing', TextType::class, [
                'attr' => [
                    'class' => 'form-control fin',
                    'step' => '1',
                    'min' => '0',
                    'max' => '100'
                ],
                'required'   => true,
                'label' => 'Отделочные работы',
                'empty_data' => 100,

            ])
            ->add('branding', TextType::class, [
                'attr' => [
                    'class' => 'form-control fin',
                    'step' => '1',
                    'min' => '0',
                    'max' => '100'
                ],
                'required'   => true,
                'label' => 'Брендирование',
                'empty_data' => 0,

            ])
            ->add('endDate', DateType::class,[
                'widget' => 'single_text',
                'required'   => true,
                'label' => 'Дата окночания'
            ])
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'   => false,
                'label' => 'Комментарий',
                'empty_data' => "-",
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'attr'     => [
                    'class' => 'form-control',
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ],
                'label' => 'Фотографии',
                'mapped' => false
            ])
            ->add('submit', SubmitType::class,[

                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'

            ])
            ->add('notPlanned', CheckboxType::class,[
                'required' => false,
                'attr' => [
                    'class' => 'btn btn-outline-success',
                ],
                'label' => 'Ремонт не запланирован',
            ]);
    }


}
