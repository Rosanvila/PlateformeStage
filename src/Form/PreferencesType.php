<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreferencesType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullVisibility', CheckboxType::class, [
                'label' => $this->translator->trans('visibility.any_user'),
                'required' => false,
            ])
            ->add('onlyContact', CheckboxType::class, [
                'label' => $this->translator->trans('visibility.only_contacts'),
                'required' => false,
            ])
            ->add('nobody', CheckboxType::class, [
                'label' => $this->translator->trans('visibility.nobody'),
                'required' => false,
            ])
            ->add('notification', CheckboxType::class, [
                'label' => $this->translator->trans('notifications.activate'),
                'required' => false,
            ])
            ->add('availability', CheckboxType::class, [
                'label' => $this->translator->trans('notifications.availability'),
                'required' => false,
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);

    }
}

