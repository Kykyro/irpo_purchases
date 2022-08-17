<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class ChoiceInputType
 */
class ChoiceInputType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'choiceType',
                ChoiceType::class,
                [
                    'required' => false,
                    'choices'  => $options['choices'],
                    'label' => 'Выберете',
                ]
            )
            ->add(
                'choiceInput',
                TextType::class,
                [
                    'required' => false,
                    'label'    => false,
                    'attr'     => [
                        'placeholder' => 'Другое',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['choices'] = $options['choices'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['choices']);

        $resolver->setDefaults(
            [
                'choices' => [],
            ]
        );

        $resolver->setDefaults([
            'allowed_states' => null,
            'is_extended_address' => false,
        ]);

        // optionally you can also restrict the options type or types (to get
        // automatic type validation and useful error messages for end users)
        $resolver->setAllowedTypes('allowed_states', ['null', 'string', 'array']);
        $resolver->setAllowedTypes('is_extended_address', 'bool');

        // optionally you can transform the given values for the options to
        // simplify the further processing of those options
        $resolver->setNormalizer('allowed_states', static function (Options $options, $states) {
            if (null === $states) {
                return $states;
            }

            if (is_string($states)) {
                $states = (array)$states;
            }

            return array_combine(array_values($states), array_values($states));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'choiceInputType';
    }
}