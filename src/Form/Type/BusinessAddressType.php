<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BusinessAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('businessAddress', TextType::class, [
                'label' => 'register.businessAddress',
                'attr' => ['placeholder' => 'address'],
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'register.city',
                'attr' => ['placeholder' => 'city'],
                'required' => true,
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'register.postalCode',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le code postal ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'Le code postal doit comporter exactement {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]*$/',
                        'message' => 'Le code postal ne peut contenir que des chiffres.',
                    ])
                ]
            ]);
    }
}