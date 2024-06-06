<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\PasswordConfirmType;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('account_secu.email'),
                'attr' => ['placeholder' => $this->translator->trans('account_secu.enter_email')],
                'required' => true,
                'mapped' => false,
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => $this->translator->trans('account_secu.current_password'),
                'attr' => ['placeholder' => '******'],
                'toggle' => true,
                'use_toggle_form_theme' => false,
                'always_empty' => false,
                'hidden_label' => 'Hide',
                'visible_label' => 'Show',
                'required' => true,
                'mapped' => false,
            ])
            ->add('plainPassword', PasswordConfirmType::class, [
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('account_secu.validate'),
                'attr' => ['class' => 'btn btn-mysecu'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
