<?php

namespace App\Form;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class mapEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $org = $options['data']->getOrganization();
        // $orgContent = $serializer->serialize($org, 'json');
        // $orgContent = $serializer->decode($org, 'json');
        $orgContent = "";
        if($org != null){
            $orgContent = $org[0];
            $orgContent = stripslashes($orgContent);
            $orgContent = trim($orgContent, '["]');
        }
        
        
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-3 '],
                'required'   => true,
                'label' => 'Заголовок'
            ])
            ->add('ident', NumberType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'required'   => true,
                'label' => 'Заголовок'
            ])
            ->add('organization', TextareaType::class, [
                'attr' => ['class' => 'form-control mb-3 '],
                'required'   => false,
                'label' => 'Заголовок',
                'mapped' => false,
                'data' => $orgContent,
//                'disabled' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-success col-lg-4 mt-3'],
                'label' => 'Готово'
            ]);
    }


}
