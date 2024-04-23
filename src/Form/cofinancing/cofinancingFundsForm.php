<?php

namespace App\Form\cofinancing;


use App\Entity\CofinancingFunds;
use App\Entity\CofinancingScenario;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class cofinancingFundsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('regionFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required' => false,
                'label' => 'Средства Субъекта'
            ])
            ->add('educationFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required' => false,
                'label' => 'Средства ОО'
            ])
            ->add('employerFunds', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '.01',
                    'min' => '0',
                    'max' => '99999999999'
                ],
                'required' => false,
                'label' => 'Средства РД'
            ])
        ;
    }

}
