<?php

namespace App\Form;

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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class articleEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'required'   => true,
                'label' => 'Заголовок'
            ])
            ->add('imgTitle', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Картинка для статьи',
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new File([
                        'maxSize' => '30M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => '',
                    ])
                ],
            ])
            ->add('content', CKEditorType::class, array(
                'attr' => ['class' => 'mb-3'],
                'config' => array(
                    'uiColor' => '#ffffff',
                    'applicationTitle' => 'Редактор статей',

                ),
            ))
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-success col-lg-4 mt-3'],
                'label' => 'Готово'
            ])
//            ->add('fileName', TextType::class, [
//                'attr' => ['class' => 'form-control mb-3'],
//                'required' => false,
//                'label' => 'Название файла',
//            ])
//            ->add('file', FileType::class, [
//                'mapped' => false,
//                'required'   => false,
//                'label' => 'Выберете файл для статьи',
//                'attr' =>[
//                    'class' => 'mb-3 form-control'
//                ]
//            ])
            ->add('createdAt', DateType::class,[
                'widget' => 'single_text',
                'required'   => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('articleFiles', CollectionType::class, [
                    'entry_type' => articleFileForm::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,

                ]
            )
            ;
    }


}
