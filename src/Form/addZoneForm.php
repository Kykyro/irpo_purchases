<?php

namespace App\Form;

use App\Entity\ClusterAddresses;
use App\Entity\User;

use App\Entity\ZoneType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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

class addZoneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'label' => 'Название зоны'
            ])
            ->add('type', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'class' => ZoneType::class,
                'choice_label' => 'name',
                'label' => 'Тип'

            ])
            ->add('doNotTake', CheckboxType::class, [
                'label' => 'НЕ считать как зону',
                'required' => false,
            ])
            ->add('addres', EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'class' => ClusterAddresses::class,
                'choice_label' => 'addresses',
                'query_builder' => function (EntityRepository $er) use($options)
                {
                    return $er->createQueryBuilder('a')
                        ->andWhere("a.user = :user")
                        ->setParameter('user', $options['attr']['user'])
//                        ->orderBy('a.organization', 'ASC')
                        ;
                },
                'label' => 'Адрес'
            ])
            ->add('submit', SubmitType::class,[

                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Сохранить'

            ]);
    }
    public function getDefaultOptions()
    {
        return array(
            'user'         => null
        );
    }

}
