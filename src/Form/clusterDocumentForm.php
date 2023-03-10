<?php

namespace App\Form;

use App\Entity\User;

use App\Entity\ZoneType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class clusterDocumentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PartnershipAgreement', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypesMessage' => '',
                    ])
                ],
                'label' => 'Соглашение о партнерстве'
            ])
            ->add('FinancialAgreement', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypesMessage' => '',
                    ])
                ],
                'label' => 'Финансовое соглашение',
            ])
            ->add('InfrastructureSheet', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypesMessage' => '',
                    ])
                ],
                'label' => 'Инфраструктурный лист',
            ])
            ->add('DesignProject', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypesMessage' => '',
                    ])
                ],
                'label' => 'Дизайн-проект',
            ])
            ->add('ActivityProgram', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypesMessage' => '',
                    ])
                ],
                'label' => 'Программа деятельности',
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'
            ])

           ;
    }


}
