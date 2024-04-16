<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form\cofinancing;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\CluterDirector;
use App\Entity\CofinancingScenarioFile;
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

class filesType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('status', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'На проверке' => 'На проверке',
                    'Принято' => 'Принято',
                    'Не принято' => 'Не принято',


                ],
                'multiple' => false,
                'expanded' => false,
                'required'   => true,
                'label' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
//        $resolver->setRequired('vars');
        $resolver->setDefaults([
            'data_class' => CofinancingScenarioFile::class,
        ]);
    }
}