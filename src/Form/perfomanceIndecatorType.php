<?php
/**
 * Created by PhpStorm.
 * User: kykyro
 * Date: 01.03.23
 * Time: 9:21
 */

namespace App\Form;
use App\Entity\ClusterPerfomanceIndicators;
use App\Entity\ZoneInfrastructureSheet;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class perfomanceIndecatorType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year', ChoiceType::class, [
                'choices'  => [

                    '2021' => 2021,
                    '2022' => 2022,
                    '2023' => 2023,
                    '2024' => 2024,
                    '2025' => 2025,
                    '2026' => 2026,
                    '2027' => 2027,
                    '2028' => 2028,
                    '2029' => 2029,

                ],
                'required'   => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Год создания кластера'
            ])
            ->add('studentCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => 'Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта "Профессионалитет", разработанных в том числе с применением автоматизированных методов конструирования указанных образовательных программ'
            ])
            ->add('programCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                            Количество реализуемых образовательных программ в интересах организации реального сектора экономики
                            '
            ])
            ->add('teacherCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                            Количество педагогических работников, владеющих актуальными педагогическими, производственными (профильными), цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики
                            '
            ])
            ->add('workerCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                          Количество работников организаций реального сектора экономики, владеющих актуальными педагогическими навыками, цифровыми навыками или навыками конструирования образовательных программ под запросы работодателей и экономики, включенных в образовательный процесс в качестве преподавателей и мастеров производственного обучения по совместительству
                            '
            ])
            ->add('studentCountWithMentor', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                          Количество обучающихся по образовательным программам среднего профессионального образования в рамках федерального проекта "Профессионалитет", разработанных, в том числе, с применением автоматизированных методов конструирования указанных образовательных программ, прошедших практическую подготовку на базе образовательно- производственного центра с закреплением наставника, работающего в организации реального сектора экономики
                            '
            ])
            ->add('jobSecurityCount', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '1',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                          Количество заключенных с гарантией трудоустройства выпускников договоров о целевом обучении по образовательным программам среднего профессионального образования в рамках федерального проекта "Профессионалитет", разработанных в том числе с применением автоматизированных методов конструирования указанных образовательных программ (единиц)
                            '
            ])
            ->add('amountOfFunding', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                          Объем финансирования (включая расходы на оплату труда преподавателей и мастеров производственного обучения) образовательных организаций, являющихся участниками центра, обеспечиваемый их учредителями, который не может быть менее объемов финансирования образовательных организаций до создания центра
                            '
            ])
            ->add('amountOfExtraFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],

                'required'   => false,
                'label' => '
                          Объем внебюджетных средств (включая стоимость безвозмездно переданного образовательным организациям, являющимся участниками центра, имущества, необходимого для реализации основных профессиональных образовательных программ, основных программ профессионального обучения и дополнительных профессиональных программ), направляемых участниками центра из числа организаций, действующих в реальном секторе экономики, на развитие центра                             
                            '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClusterPerfomanceIndicators::class,
        ]);
    }
}