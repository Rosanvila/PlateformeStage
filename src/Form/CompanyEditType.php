<?php

namespace App\Form;

use App\Entity\Company;
use App\Form\Type\UserEditType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'register.company',
            ])
            ->add('users', CollectionType::class, [
                'entry_type' => UserEditType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('businessAddress', TextType::class, [
                'label' => 'register.businessAddress',
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'register.postalCode',
            ])
            ->add('city', TextType::class, [
                'label' => 'register.city',
            ])
            ->add('about', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'edit.about'],
            ])
            ->add('logo', FileType::class, [
                'label' => 'edit.logo',
                'mapped' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'edit.save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}