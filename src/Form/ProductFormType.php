<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', ChoiceType::class, [
                'label' => 'Quantity',
                'choices' => range(1, 50),
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a quantity']),
                    new GreaterThanOrEqual(1, message: 'The minimum quantity is 1'),
                    new LessThanOrEqual(50, message: 'The maximum quantity is 50'),
                ],
                'required' => true,
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Duration (in years)',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a duration']),
                    new GreaterThanOrEqual(1, message: 'The minimum duration is 1 year'),
                    new LessThanOrEqual(5, message: 'The maximum duration is 5 years'),
                ],
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add to Cart',
                'attr' => ['class' => 'btn btn-mysecu m-auto d-block']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
