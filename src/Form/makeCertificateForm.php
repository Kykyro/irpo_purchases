<?php

namespace App\Form;

use App\Entity\User;

use App\Entity\ZoneType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class makeCertificateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clusters', EntityType::class, [
//                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'label' => 'Адрес',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%REGION%')
                        ;
                    },
                'choice_label' => function ($clusters) {
                    return $clusters->getUserInfo()->getCluster();
                },
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => ChoiceList::attr($this, function (?User $user) {
                    return $user ? [
                        'data-year' => $user->getUserInfo()->getYear(),
                        'data-region' => is_null($user->getUserInfo()->getRfSubject()) ? '' : $user->getUserInfo()->getRfSubject()->getName()
                    ]
                    : [];
                }),

            ])

            ->add('submit', SubmitType::class,[

                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Добавить'

            ]);
    }


}

