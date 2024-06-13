<?php

namespace App\Form;

use App\Entity\Company;
use App\Form\Type\UserEditType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'required' => false,
            ])
            ->add('users', CollectionType::class, [
                'entry_type' => UserEditType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
            ])
            ->add('businessAddress', TextType::class, [
                'label' => 'register.businessAddress',
                'required' => false,
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'register.postalCode',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'register.city',
                'required' => false,
            ])
            ->add('about', TextareaType::class, [
                'label' => 'edit.about',
                'label_attr' => ['style' => 'margin-top: 1rem;'],
                'attr' => [
                    'rows' => '1',
                    'style' => 'resize: vertical; max-height: 15rem;'
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