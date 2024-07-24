<?php

namespace App\Twig\Components;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\InvitationRepository;
use App\Service\RegisterInvitation\PreFillFields;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Security\EmailVerifier;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsLiveComponent]
class RegisterForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    private Address|string|null $senderAddress = '';

    private User $user;
    private RequestStack $requestStack;
    private InvitationRepository $invitationRepository;
    private PreFillFields $PreFillFields;
    #[LiveProp()]
    public string $base64Photo = '';

    /**
     * The initial data used to create the form.
     */
    #[LiveProp()]
    public ?User $initialFormData = null;

    #[LiveProp]
    public string $photoUploadError = '';

    public function __construct(
        private readonly ValidatorInterface                                $validator,
        private EmailVerifier                                              $emailVerifier,
        #[Autowire(env: 'RESET_PASSWORD_SUBJECT')] private readonly string $subject,
        #[Autowire(env: 'AUTH_CODE_SENDER_EMAIL')] string|null             $senderEmail,
        #[Autowire(env: 'AUTH_CODE_SENDER_NAME')] ?string                  $senderName = null,
        RequestStack                                                       $requestStack,
        InvitationRepository                                               $invitationRepository,
        PreFillFields                                                      $PreFillFields
    )
    {
        $this->user = new User();
        $this->requestStack = $requestStack;
        $this->invitationRepository = $invitationRepository;
        $this->PreFillFields = $PreFillFields;
        if (null !== $senderEmail && null !== $senderName) {
            $this->senderAddress = new Address($senderEmail, $senderName);
        } elseif (null !== $senderEmail) {
            $this->senderAddress = $senderEmail;
        }
    }

    public function preFillForm(FormInterface $form, Invitation $invitation): void
    {
        $this->PreFillFields->invitationFillField($form, $invitation);
    }

    protected function instantiateForm(): FormInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        $token = $request->attributes->get('token');
        $form = $this->createForm(RegistrationFormType::class, $this->initialFormData);

        if ($token) {
            $invitation = $this->invitationRepository->findOneBy(['uuid' => $token]);
            if ($invitation) {
                $this->PreFillForm($form, $invitation);
            }
        }
        return $form;
    }

    #[LiveAction]
    public function updatePicturePreview(Request $request)
    {
        $this->photoUploadError = '';
        $file = $request->files->get('registration_form')['picture'];
        if ($file instanceof UploadedFile) {
            $this->validateSingleFile($file);
            $this->base64Photo = base64_encode(file_get_contents($file->getPathname()));
            $this->dispatchBrowserEvent('picture:changed', ["base64" => $this->base64Photo]);
        }
    }

    private function validateSingleFile(UploadedFile $singleFileUpload): void
    {
        $errors = $this->validator->validate($singleFileUpload, [
            new Image([
                'maxSize' => '5M',
                'mimeTypes' => [
                    'image/png',
                    'image/jpeg',
                ],
            ]),
        ]);

        if (0 === \count($errors)) {
            return;
        }

        $this->photoUploadError = $errors->get(0)->getMessage();
        $this->dispatchBrowserEvent('picture:changed', ["base64" => ""]);

        // causes the component to re-render
        throw new UnprocessableEntityHttpException('Validation failed');
    }

    #[LiveAction]
    public function save(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->submitForm();
        $this->user = $this->getForm()->getData();

        //Firstname & Lastname
        $this->user->setFirstname($this->getForm()->get('firstname')->get('firstnameField')->getData());
        $this->user->setLastname($this->getForm()->get('lastname')->get('lastnameField')->getData());

        // Mot de passe
        $this->user->setPassword(
            $userPasswordHasher->hashPassword(
                $this->user,
                $this->getForm()->get('plainPassword')->get('password')->getData()
            )
        );

        // Photo
        if (!is_null($this->base64Photo) && !empty($this->base64Photo)) {
            $this->user->setPhoto($this->base64Photo);
        }

        // Role
        $this->user->addRole('ROLE_' . $this->getForm()->get('function')->getData());

        // Si on est pas vendeur
        if (in_array($this->getForm()->get('function')->getData(), ['freemium', 'premium'])) {
            // Company champ libre
            $this->user->setCompany($this->getForm()->get('company')->get('companyField')->getData());
            $this->user->setBusinessAddress($this->getForm()->get('businessAddress')->get('businessAddressField')->getData());
            $this->user->setPostalCode($this->getForm()->get('postalCode')->get('postalCodeField')->getData());
            $this->user->setCity($this->getForm()->get('city')->get('cityField')->getData());
        } else {
            // Il va falloir créer une entreprise
            $this->user->setBusinessAddress(null);
            $this->user->setPostalCode(null);
            $this->user->setCity(null);

            $company = new Company();
            $company->setName($this->getForm()->get('company')->get('companyField')->getData());
            $company->setBusinessAddress($this->getForm()->get('businessAddress')->get('businessAddressField')->getData());
            // ajout des champs city et postalCode
            $company->setPostalCode($this->getForm()->get('postalCode')->get('postalCodeField')->getData());
            $company->setCity($this->getForm()->get('city')->get('cityField')->getData());
            $company->setSiretNumber($this->getForm()->get('siretNumber')->getData());
            $company->setVatNumber($this->getForm()->get('vatNumber')->getData());
            $company->setOwner($this->user);
            $entityManager->persist($company);

            $this->user->setVendorCompany($company);
        }

        $entityManager->persist($this->user);
        $entityManager->flush();

        $templatedEmail = (new TemplatedEmail())
            ->to($this->user->getEmail())
            ->subject($translator->trans('mailer.register.subject', [], 'messages'))
            ->htmlTemplate('registration/confirmation_email.html.twig');

        if (null !== $this->senderAddress) {
            $templatedEmail->from($this->senderAddress);
        }

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $this->user, $templatedEmail
        );

        $security->login($this->user, 'form_login', 'main');
        return $this->redirectToRoute('app_index');

    }
}