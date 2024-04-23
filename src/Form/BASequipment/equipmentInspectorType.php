<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form\BASequipment;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\CluterDirector;
use App\Entity\CofinancingScenarioFile;
use App\Entity\EmployersContact;
use App\Entity\ResponsibleContact;
use App\Entity\UAVsTypeEquipment;
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

class equipmentInspectorType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('deliveredCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('deliveredSum', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('contractedCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('contractedSum', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('purchaseCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('purchaseSum', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('planCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('planSum', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required'   => false,
                'label' => false
            ])
            ->add('provide', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'required'   => false,
                'label' => false
            ])
            ->add('manufacturec', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'required'   => false,
                'label' => false
            ])
            ->add('mark', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'required'   => false,
                'label' => false
            ])
            ->add('model', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'required'   => false,
                'label' => false
            ])
            ->add('okpd2', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'required'   => false,
                'label' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
//        $resolver->setRequired('vars');
        $resolver->setDefaults([
            'data_class' => UAVsTypeEquipment::class,
        ]);
    }
}