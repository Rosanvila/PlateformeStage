<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\BusinessAddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Translation\TranslatableMessage;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Type\PasswordConfirmType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('email', EmailType::class, [
                'label' => 'login.email_adress',
                'attr' => ['placeholder' => 'email'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'register.lastname',
                'attr' => ['placeholder' => 'lastname'],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'register.firstname',
                'attr' => ['placeholder' => 'firstname'],
            ])
            ->add('language', ChoiceType::class, [
                'label' => 'Language',
                'choices' => [
                    'French' => 'fr',
                    'English' => 'en',
                ],
                'empty_data' => 'fr',
            ])
            ->add('job', TextType::class, [
                'label' => 'register.job',
                'attr' => ['placeholder' => 'job'],
                'required' => true,
            ])
            ->add('company', TextType::class, [
                'label' => 'register.company',
                'attr' => ['placeholder' => 'society'],
                'mapped' => false,
                'required' => true,
            ])
            ->add('expertise', ChoiceType::class, [
                'choices' => [
                    "expertise.infrastructures" => "infrastructures",
                    "expertise.security" => "security",
                    "expertise.network" => "network",
                    "expertise.cloud" => "cloud",
                    "expertise.storage" => "storage",
                    "expertise.backup" => "backup",
                    "expertise.services" => "services"
                ],
                'expanded' => false,
                'multiple' => true,
                'autocomplete' => true,
                'label' => 'register.expertise',
            ])
            ->add('companyAddress', BusinessAddressType::class,
                ['label' => false,
                    'required' => true,
                    'mapped' => false,
                ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => 'register.picture_format',
                'required' => false,
                'attr' => [
                    'data-action' =>"change->live#action:prevent",
                    'data-live-action-param' => "files|updatePicturePreview",
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => new TranslatableMessage('register.accept_terms', ['%href%' => '#']),
                'label_html' => true,
                'mapped' => false,
                'required' => true,
                'row_attr' => ['class' => 'd-flex justify-content-center'],
            ])
            ->add('function', ChoiceType::class, [
                'label' => 'register.function',
                'mapped' => false,
                'choices' => [
                    'Freemium' => "freemium",
                    'Premium' => "premium",
                    'Vendor' => "vendor",
                ],
                'empty_data' => 'freemium'
            ])
            ->add('plainPassword', PasswordConfirmType::class, [
                'mapped' => false,
            ])
            ->addDependent('submit', 'function', function (DependentField $field, ?string $function) {
                $submitOptions = [
                    'attr' => ['class' => 'btn btn-mysecu'],
                ];

                if ($function === "freemium" || $function === null) {
                    $submitOptions["label"] = "register.submit_freemium";
                } else {
                    $submitOptions["label"] = "register.submit_not_freemium";
                }

                $field->add(SubmitType::class, $submitOptions);
            });

        // Partie entreprise si vendor
        $builder->addDependent('siretNumber', 'function', function (DependentField $field, ?string $function) {
            if (in_array($function, ["freemium", "premium"]) || $function === null) {
                return;
            } else {
                $field->add(NumberType::class, [
                    'label' => 'register.siretNumber',
                    'attr' => ['placeholder' => 'siretNumber'],
                    'mapped' => false,
                    'required' => true,
                ]);
            }
        });
        $builder->addDependent('vatNumber', 'function', function (DependentField $field, ?string $function) {
            if (in_array($function, ["freemium", "premium"]) || $function === null) {
                return;
            } else {
                $field->add(null, [
                    'label' => 'register.vatNumber',
                    'attr' => ['placeholder' => 'vatNumber'],
                    'mapped' => false,
                    'required' => true,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

/*function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder = new DynamicFormBuilder($builder);

    for ($i = 1; $i <= 5; $i++) {
        $builder->add('vendor' . $i, EmailType::class, [
            'label' => 'Vendor ' . $i,
            'attr' => ['placeholder' => 'email'],
        ]);
    }
}*/
