<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullVisibility', CheckboxType::class, [
                'label' => 'Any user',
            ])
            ->add('onlyContact', CheckboxType::class, [
                'label' => 'Only users in my contact list',
            ])
            ->add('nobody', CheckboxType::class, [
                'label' => 'Nobody'
            ])
            ->add('notification', CheckboxType::class, [
                'label' => 'Activates notifications',
            ])
            ->add('availability', CheckboxType::class, [
                'label' => 'Activates my availability',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);

    }
}

