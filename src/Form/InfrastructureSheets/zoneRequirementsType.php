<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form\InfrastructureSheets;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\CluterDirector;
use App\Entity\EmployersContact;
use App\Entity\ResponsibleContact;
use App\Entity\ZoneInfrastructureSheet;
use App\Entity\ZoneRequirements;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class zoneRequirementsType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


//            ->add('workplaceCount', NumberType::class, [
//                'attr' => [
//                    'class' => 'form-control mb-3',
//
//                ],
//                'required'   => false,
//                'html5' => true,
//
//                'label' => 'Количество рабочих мест:'
//            ])
            ->add('area', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'required'   => false,
                'label' => 'Площадь зоны (кв.м.):',
                'html5' => true,
            ])
            ->add('lighting', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',

                ],
                'required'   => false,
                'label' => 'Освещение (вид освещения и источника):'
            ])
            ->add('internet', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',

                ],
                'choices'  => [
                    'Проводной' => 'Проводной',
                    'Беспроводной' => 'Беспроводной',
                    'Проводной и беспроводной' => 'Проводной и беспроводной',
                    'Не требуется' => 'Не требуется'
                ],
                'multiple' => false,
                'expanded' => false,

                'required'   => false,
                'label' => 'Интернет'
            ])
            ->add('electricity', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',

                ],
                'choices'  => [
                    '220В' => '220В',
                    '380В' => '380В',
                    '220В и 380В' => '220В и 380В',
                    'Не требуется' => 'Не требуется'

                ],
                'multiple' => false,
                'expanded' => false,

                'required'   => false,
                'label' => 'Электричество (Подключения к сети): '
            ])
            ->add('groundLoop', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',

                ],
                'choices'  => [
                    'Требуется' => 'Требуется',
                    'Не требуется' => 'Не требуется'

                ],
                'multiple' => false,
                'expanded' => false,

                'required'   => false,
                'label' => 'Контур заземления для электропитания и сети слаботочных подключений: '
            ])
            ->add('floor', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'required'   => false,
                'label' => 'Покрытие пола (вид покрытия):'
            ])
            ->add('water', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',

                ],
                'choices'  => [
                    'Требуется' => 'Требуется',
                    'Не требуется' => 'Не требуется'

                ],
                'multiple' => false,
                'expanded' => false,

                'required'   => false,
                'label' => 'Подведение/ отведение ГХВС: '
            ])
            ->add('compressedAir', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',

                ],
                'choices'  => [
                    'Требуется' => 'Требуется',
                    'Не требуется' => 'Не требуется'

                ],
                'multiple' => false,
                'expanded' => false,

                'required'   => false,
                'label' => 'Подведение сжатого воздуха: '
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ZoneRequirements::class,
        ]);
    }
}