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
//            ->add('type', EntityType::class, [
//                'attr' => [
//                    'class' => 'form-control',
//                ],
//                'class' => \App\Entity\EquipmentType::class,
//                'choice_label' => 'name',
//                'required'   => false,
//                'label' => false,
//                'query_builder' => function (EntityRepository $er) use ($options) {
//                    return $er->createQueryBuilder('eq')
//                        ->andWhere('eq.isHide = 0')
//                        ->andWhere('eq.type LIKE :type')
//                        ->setParameter('type', '%'.$options['vars']['type'].'%')
//                        ->orderBy('eq.name', 'ASC');
//                },
//            ])
            ->add('count', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number'
                ],
                'required'   => false,
                'label' => false
            ])
//            ->add('unit', EntityType::class, [
//                'attr' => [
//                    'class' => 'form-control',
//                ],
//                'class' => \App\Entity\WorkzoneEqupmentUnit::class,
//                'choice_label' => 'name',
//                'required'   => false,
//                'label' => false,
//                'query_builder' => function (EntityRepository $er) use ($options) {
//                    return $er->createQueryBuilder('u')
//                        ->andWhere('u.isHide = 0')
//                        ->andWhere('u.type LIKE :type')
//                        ->setParameter('type', '%'.$options['vars']['type'].'%')
//                        ->orderBy('u.name', 'ASC');
//                },
//            ])
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
                'required'   => false,
                'label' => false
            ])
            ->add('type', ChoiceType::class, [
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
                'required'   => false,
                'label' => false
            ])
            ->add('unit', ChoiceType::class, [
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
                'required'   => false,
                'label' => false
            ])
            ->add('ZoneGroup', HiddenType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => false,
                'label' => false,
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('vars');
        $resolver->setDefaults([
            'data_class' => WorkzoneEquipment::class,
        ]);
    }
}