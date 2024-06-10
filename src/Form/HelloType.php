<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class HelloType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('hello.name'), 'class' => 'form-control'],
                'required' => true,
                'mapped' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('hello.email'), 'class' => 'form-control'],
                'required' => true,
                'mapped' => false,
            ])
            ->add('motif', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    $this->translator->trans('hello.motif_choices.contact') => 'contact',
                    $this->translator->trans('hello.motif_choices.recommendation') => 'recommendation',
                    $this->translator->trans('hello.motif_choices.other') => 'other',
                ],
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'mapped' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('hello.message'), 'class' => 'form-control', 'rows' => '4'],
                'required' => true,
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('hello.submit'),
                'attr' => ['class' => 'd-flex text-primary text-uppercase text-decoration-underline fs-5 fw-medium'],
            ]);
    }
}