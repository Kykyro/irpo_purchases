<?php

namespace App\Form;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InspectorPurchasesFindFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("rf_subject", EntityType::class, [
            'attr' => ['class' => 'form-control'],
            'required'   => true,
            'class' => RfSubject::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('sub')
                    ->orderBy('sub.name', 'ASC');
            },
            'choice_label' => 'name',
        ]);
        $builder->add('year', ChoiceType::class, [
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
                '2030' => 2030,
            ],
            'required'   => true,
        ]);


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        });
    }
}