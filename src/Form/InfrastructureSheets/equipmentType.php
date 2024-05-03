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
use App\Entity\WorkzoneEquipment;
use App\Entity\ZoneInfrastructureSheet;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class equipmentType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $zoneGroup = $options['zoneGroup'][0];
        $builder
            ->add('name', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'oninput' => 'this.style.height = "";this.style.height = this.scrollHeight + "px"',
                ],
                'required'   => false,
                'label' => false,
            ])
            ->add('specification', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'oninput' => 'this.style.height = "";this.style.height = this.scrollHeight + "px"',
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('count', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('resultCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number'
                ],
                'required'   => false,
                'label' => false,
                'mapped' => false
            ])
            ->add('funds', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'ФБ' => 'ФБ',
                    'БР' => 'БР',
                    'ВБ' => 'ВБ',
                    'РБ' => 'РБ',
                    'В наличии' => 'В наличии'

                ],
                'multiple' => false,
                'expanded' => false,
                'required'   => true,
                'label' => false
            ])
            ->add('clusterComment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'oninput' => 'this.style.height = "";this.style.height = this.scrollHeight + "px"',
                ],
                'required'   => false,
                'label' => false,
            ])
        ;
        if($zoneGroup->getType() == 'Охрана труда и техника безопасности ')
        {
            $builder
                ->add('type', ChoiceType::class, [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => [
                        'Мебель' => 'Мебель',
                        'Оборудование' => 'Оборудование',
                        'Оборудование IT' => 'Оборудование IT',
                        'Программное обеспечение' => 'Программное обеспечение',
                    ],
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'label' => false
                ]);
        }
        else{
            $builder
                ->add('type', ChoiceType::class, [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => [
                        'Охрана труда' => 'Охрана труда',
                        'Техника безопасности' => 'Техника безопасности',
                    ],
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'label' => false
                ]);
        }

        if($zoneGroup->getType() == 'Рабочее место учащегося')
        {
            $builder
                ->add('unit', ChoiceType::class, [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices'  => [
                        'шт. (на 1 раб. место)' => 'шт. (на 1 раб. место)',
                        'шт. (на 2 раб. места)' => 'шт. (на 2 раб. места)',

                    ],
                    'multiple' => false,
                    'expanded' => false,
                    'required'   => true,
                    'label' => false
                ]);
        }
        else{
            $builder
                ->add('unit', ChoiceType::class, [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices'  => [
                        'шт.' => 'шт.',
                    ],
                    'multiple' => false,
                    'expanded' => false,
                    'required'   => true,
                    'label' => false
                ]);
        }


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('zoneGroup');
        $resolver->setDefaults([
            'data_class' => WorkzoneEquipment::class,
        ]);
    }
}