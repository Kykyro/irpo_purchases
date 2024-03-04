<?php

namespace App\Form;

use App\Entity\ContactInfo;
use App\Entity\User;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

class contactInfoAddEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('director', contactDirectorType::class, [

                'label' => false,

            ]
            )
            ->add('responsibleContacts', CollectionType::class, [
                    'entry_type' => contactResponsibleType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,

                    'label' => false,

                ]
            )
            ->add('employersContacts', CollectionType::class, [
                    'entry_type' => contactEmployersType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,

                    'label' => false,

                ]
            )
            ->add('addContacts', CollectionType::class, [
                    'entry_type' => contactAddType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,

                    'label' => false,

                ]
            )

            ->add('submit', SubmitType::class,[

                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactInfo::class,
        ]);
    }
}
