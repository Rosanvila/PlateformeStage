<?php

namespace App\Form\Type;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use symfony\Component\OptionsResolver\OptionsResolver;

class BusinessAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('businessAddressField', TextType::class, [
                'label' => 'register.businessAddress',
                'attr' => ['placeholder' => 'register.businessAddress'],
                'required' => true,
            ]);
    }

    //data_class is set to User because the form is used to update the User entity
/*    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }*/
}