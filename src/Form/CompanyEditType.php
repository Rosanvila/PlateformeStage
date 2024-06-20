<?php

namespace App\Form;

use App\Entity\Company;
use App\Form\Type\BusinessAddressType;
use App\Form\Type\CityType;
use App\Form\Type\CompanyNameType;
use App\Form\Type\PostalCodeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', CompanyNameType::class, [
                'required' => false,
                'mapped' => false,
            ])
            /*->add('owner', UsernameEditFormType::class, [
                'required' => false,
            ])*/
            ->add('name', CompanyNameType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('businessAddress', BusinessAddressType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('postalCode', PostalCodeType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('city', CityType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('about', TextareaType::class, [
                'label' => 'edit.about',
                'attr' => [
                    'style' => 'height: 6.25rem; max-height: 13rem; resize: vertical;',
                    'placeholder' => 'edit.about_placeholder',
                ],
                'required' => false,
            ])
            ->add('logo', FileType::class, [
                'label' => 'edit.logo',
                'mapped' => false,
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'edit.save',
                'attr' => ['class' => 'btn btn-mysecu border-light mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}