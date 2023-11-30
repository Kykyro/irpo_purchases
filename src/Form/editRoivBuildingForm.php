<?php

namespace App\Form;

use App\Entity\BuildingCategory;
use App\Entity\BuildingPriority;
use App\Entity\User;

use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

class editRoivBuildingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Наименование здания',


            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Адрес',


            ])
            ->add('area', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Общая площадь',


            ])
            ->add('repairArea', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Площадь капитального ремонта',


            ])
            ->add('neededFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Необходимый объем средств',


            ])
            ->add('possibleFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Средства региона',


            ])
            ->add('addFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Необходимый дополнительных средств объем средств',


            ])
            ->add('buildingCategory', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Категория здания',
                'class' => BuildingCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',

            ])
            ->add('buildingPriority', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => true,
                'label' => 'Приоритетность',
                'class' => BuildingPriority::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',

            ])


            ->add('submit', SubmitType::class,[

                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'

            ])

        ;

    }


}
