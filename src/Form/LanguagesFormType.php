<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguagesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('language', ChoiceType::class, [
                'label' => 'Language',
                'choices' => [
                    'form.language.english' => 'en',
                    'form.language.french' => 'fr',
                ],
                'choice_translation_domain' => 'messages',
                'attr' => ['class' => 'form-select form-select-lg my-3'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'hello.submit',
                'attr' => ['class' => 'btn btn-mysecu mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'supported_locales' => [],
        ]);
    }
}