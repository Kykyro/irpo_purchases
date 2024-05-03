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
use App\Entity\ZoneGroup;
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

class zoneGroupType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('workplaces', TextType::class, [
                'attr' => [
                    'class' => 'form-control workplaces',
                    'type' => 'number'
                ],
                'required'   => false,
                'label' => 'Количество рабочих мест'
            ])
            ->add('isDeleted', HiddenType::class, [
                'attr' => [
                ],
                'required'   => false,
                'mapped' => false,
                'data' => 1
//                'label' => 'Количество рабочих мест'
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control group-type',
                ],
                'choices'  => [
                    'Рабочее место учащегося' => 'Рабочее место учащегося',
                    'Общая зона' => 'Общая зона',
                    'Рабочее место преподавателя' => 'Рабочее место преподавателя/мастера производственного обучения',
                    'Охрана труда и техника безопасности' => 'Охрана труда и техника безопасности',


                ],
                'multiple' => false,
                'expanded' => false,
                'required'   => true,
                'label' =>  'Вид'
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
//        $resolver->setRequired('vars');
        $resolver->setDefaults([
            'data_class' => ZoneGroup::class,
        ]);
    }
}