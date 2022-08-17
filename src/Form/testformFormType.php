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

class testformFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'ChoiceInputType',
            ChoiceInputType::class,
            [
                'attr' => [
                    'class' => 'form-control'
                ],
                'mapped'  => false,
                'block_prefix' => 'choiceInputType',
                'label'   => false,
                'choices' => [
                    'Единственный поставщик' => 'Единственный поставщик',
                    'Аукцион в электронной форме' => 'Аукцион в электронной форме',
                    'Открытый конкурс' => 'Открытый конкурс',
                    'Запрос котировок' => 'Запрос котировок',
                    'Электронный магазин' => 'Электронный магазин',
                ]
            ]
        );


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        });
    }
}