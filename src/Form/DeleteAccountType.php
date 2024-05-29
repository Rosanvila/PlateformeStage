<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'attr' => ['placeholder' => 'example@example.com'],
                'required' => true,
                'translation_domain' => 'security',
                'mapped' => false,
                'autocomplete' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'toggle' => true,
                'attr' => ['placeholder' => 'Enter your password'],
                'required' => true,
                'translation_domain' => 'security',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
