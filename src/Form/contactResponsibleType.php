<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\CluterDirector;
use App\Entity\ResponsibleContact;
use App\Entity\ResponsibleContactType;
use App\Entity\ZoneInfrastructureSheet;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class contactResponsibleType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('FIO', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => 'ФИО'
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],

                'required'   => false,
                'label' => '
                      Мобильный телефон
                            '
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],

                'required'   => false,
                'label' => '
                           Почта
                            '
            ])
            ->add('responsibleContactTypes', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => ResponsibleContactType::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('c');
//                },
                'choice_label' => 'name',
                'required'   => false,
                'multiple' => true,
                'expanded' => true,
                'label' => '
                           Отвественный за ...
                            '
            ])




        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResponsibleContact::class,
        ]);
    }
}