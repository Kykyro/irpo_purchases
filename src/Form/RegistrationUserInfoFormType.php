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

class RegistrationUserInfoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("rf_subject", EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'class' => RfSubject::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sub')
                        ->orderBy('sub.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Субъект РФ'
            ])
            ->add("organization", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Наименование организации грантополучателя '
                ])
            ->add("educational_organization", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Наименование базовой образовательной организации'
                ])
            ->add("cluster", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Наименование кластера '
                ])
            ->add("declared_industry", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => true,
                    'label' => 'Заявленная отрасль'
                ])
            ->add('year', ChoiceType::class, [
                'choices'  => [

                    '2023' => 2023,
                    '2024' => 2024,

                ],
                'required'   => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Год создания кластера'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
        ]);
    }
}
