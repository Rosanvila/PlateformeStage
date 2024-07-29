<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\BusinessAddressType;
use App\Form\Type\CityType;
use App\Form\Type\CompanyNameType;
use App\Form\Type\FirstnameType;
use App\Form\Type\LastnameType;
use App\Form\Type\PostalCodeType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Type\PasswordConfirmType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $invitation = $options['invitation'];
        $isInvitationInSession = $invitation !== null;
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('email', EmailType::class, [
                'label' => 'login.email_adress',
                'attr' => ['placeholder' => 'email'],
                'required' => true,
                'disabled' => $isInvitationInSession,
            ])
            ->add('lastname', LastnameType::class, [
                'required' => true,
                'mapped' => false,
            ])
            ->add('firstname', FirstnameType::class, [
                'required' => true,
                'mapped' => false,
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
            ->add('company', CompanyNameType::class, [
                'required' => true,
                'mapped' => false,
                'disabled' => $isInvitationInSession,
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
            ->add('businessAddress', BusinessAddressType::class, [
                'required' => true,
                'mapped' => false,
                'disabled' => $isInvitationInSession,
            ])
            ->add('city', CityType::class, [
                'required' => true,
                'mapped' => false,
                'disabled' => $isInvitationInSession,
            ])
            ->add('postalCode', PostalCodeType::class, [
                'required' => true,
                'mapped' => false,
                'disabled' => $isInvitationInSession,
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => 'register.picture_format',
                'required' => false,
                'attr' => [
                    'data-action' => "change->live#action:prevent",
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
                'disabled' => $isInvitationInSession,
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

        if ($isInvitationInSession) {
            $builder
                ->add('siretNumber', NumberType::class, [
                    'label' => 'register.siretNumber',
                    'attr' => [
                        'placeholder' => 'siretNumber',
                        'disabled' => true
                    ],
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le numéro de SIRET ne peut pas être vide.',
                        ]),
                        new Length([
                            'min' => 14,
                            'max' => 14,
                            'exactMessage' => 'Le numéro de SIRET doit comporter exactement {{ limit }} chiffres.',
                        ]),
                        new Regex([
                            'pattern' => '/^[0-9]*$/',
                            'message' => 'Le numéro de SIRET ne peut contenir que des chiffres.',
                        ]),
                    ],
                ])
                ->add('vatNumber', TextType::class, [
                    'label' => 'register.vatNumber',
                    'attr' => [
                        'placeholder' => 'vatNumber',
                        'disabled' => true
                    ],
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le numéro de TVA ne peut pas être vide.',
                        ]),
                        new Regex([
                            'pattern' => '/^FR[A-Z0-9]{2}[0-9]{9}$/',
                            'message' => 'Le numéro de TVA doit suivre le format FRXX999999999.',
                        ]),
                    ]
                ]);
        } else {
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
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Le numéro de SIRET ne peut pas être vide.',
                            ]),
                            new Length([
                                'min' => 14,
                                'max' => 14,
                                'exactMessage' => 'Le numéro de SIRET doit comporter exactement {{ limit }} chiffres.',
                            ]),
                            new Regex([
                                'pattern' => '/^[0-9]*$/',
                                'message' => 'Le numéro de SIRET ne peut contenir que des chiffres.',
                            ]),
                        ],
                    ]);
                }
            });
            $builder->addDependent('vatNumber', 'function', function (DependentField $field, ?string $function) {
                if (in_array($function, ["freemium", "premium"]) || $function === null) {
                    return;
                } else {
                    $field->add(TextType::class, [
                        'label' => 'register.vatNumber',
                        'attr' => ['placeholder' => 'vatNumber'],
                        'mapped' => false,
                        'required' => true,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Le numéro de TVA ne peut pas être vide.',
                            ]),
                            new Regex([
                                'pattern' => '/^FR[A-Z0-9]{2}[0-9]{9}$/',
                                'message' => 'Le numéro de TVA doit suivre le format FRXX999999999.',
                            ]),
                        ]
                    ]);
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'invitation' => null,
        ]);
    }
}
