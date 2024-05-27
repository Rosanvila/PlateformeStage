<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordConfirmType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'toggle' => true,
                'use_toggle_form_theme' => false,
                'always_empty' => false,
                'hidden_label' => null,
                'visible_label' => null,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new PasswordStrength([], 1),
                    new NotCompromisedPassword(),
                ],
                'label' => 'New password',
                'attr' => ['placeholder' => 'New password'],
                'translation_domain' => 'security'
            ],
            'second_options' => [
                'label' => 'Repeat Password',
                'attr' => ['placeholder' => 'Repeat Password'],
                'translation_domain' => 'security'
            ],
            'invalid_message' => 'The password fields must match.',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
        ])
        ;
    }
}