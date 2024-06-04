<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\PasswordConfirmType;

class ChangePasswordFormType extends AbstractType
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

            ])
            ->add('currentPassword', PasswordType::class, [
                'toggle' => true,
                'label' => 'Current Password',
                'attr' => ['placeholder' => 'Enter your current password'],
                'required' => true,
                'translation_domain' => 'security',
                'mapped' => false,
            ])
            ->add('plainPassword', PasswordConfirmType::class, [
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Change Password',
                'attr' => ['class' => 'btn btn-mysecu'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
