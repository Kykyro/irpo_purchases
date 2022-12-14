<?php

namespace App\Form;

use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class purchasesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $is_disabled = false;

        $builder->add("PurchaseObject", TextType::class,
            [
                'attr' => ['class' => 'form-control'],
                'required'   => true,
                'disabled' => $is_disabled
            ])
            ->add("MethodOfDetermining", ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control MethodOfDetermining'
                    ],
                    'required'   => true,
                    'disabled' => $is_disabled,
                    'choices'  => [
                        'Другое' => 'Другое',
                        'Единственный поставщик' => 'Единственный поставщик',
                        'Аукцион в электронной форме' => 'Аукцион в электронной форме',
                        'Открытый конкурс' => 'Открытый конкурс',
                        'Запрос котировок' => 'Запрос котировок',
                        'Электронный магазин' => 'Электронный магазин',

                    ]
                ])
            ->add("PurchaseLink", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("PurchaseNumber", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("DateOfConclusion", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("DeliveryTime", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("Comments", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialFederalFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialFundsOfSubject", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialEmployersFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("initialEducationalOrgFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control initial',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("supplierName", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("supplierINN", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("supplierKPP", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finFederalFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control fin',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finFundsOfSubject", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control fin',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finEmployersFunds", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control fin',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("finFundsOfEducationalOrg", TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control fin',
                        'step' => '.01',
                        'min' => '0',
                        'max' => '99999999999'
                    ],
                    'required'   => false,
                    'disabled' => $is_disabled
                ])
            ->add("publicationDate", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("deadlineDate", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("dateOfSummingUp", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("postponementDate", DateType::class,[
                'widget' => 'single_text',
                'disabled' => $is_disabled,
                'required'   => false,

            ])
            ->add("postonementComment", TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'required'   => false,
                    'disabled' => $is_disabled
            ])
        ->add("anotherMethodOfDetermining", TextType::class,
            [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required'   => false,
                'disabled' => $is_disabled,
                'mapped' => false
            ]
        )
        ->add('save', SubmitType::class,
        [
            'attr' => [
                'class' => 'btn btn-outline-success',
            ],
            'label' => 'Сохранить и вернуться на главную',
        ])
        ->add('saveAndAddNew', SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-outline-success',
                ],
                'label' => 'Сохранить и добавить следующую закупку',

            ])
        ->add('file', FileType::class, [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
//                        'mimeTypes' => [
//                            'application/pdf',
//                            'application/x-pdf',
//                        ],
                        'mimeTypesMessage' => '',
                    ])
                ],
            ]);


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        });
    }
}