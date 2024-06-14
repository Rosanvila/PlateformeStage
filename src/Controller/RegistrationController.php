<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RegistrationController extends AbstractController
{
    private Address|string|null $senderAddress;

    public function __construct(
        private EmailVerifier                                              $emailVerifier,
        #[Autowire(env: 'RESET_PASSWORD_SUBJECT')] private readonly string $subject,
        #[Autowire(env: 'AUTH_CODE_SENDER_EMAIL')] string|null             $senderEmail,
        #[Autowire(env: 'AUTH_CODE_SENDER_NAME')] ?string                  $senderName = null,)
    {
        if (null !== $senderEmail && null !== $senderName) {
            $this->senderAddress = new Address($senderEmail, $senderName);
        } elseif (null !== $senderEmail) {
            $this->senderAddress = $senderEmail;
        }
    }

    #[Route(
        path: '/register',
        name: 'app_register',
    )]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->get('password')->getData()
                )
            );

            // Photo
            if (!is_null($form->get('picture')->getData())) {
                $user->setPhoto(base64_encode($form->get('picture')->getData()->getContent()));
            }

            // Role
            $user->addRole('ROLE_' . $form->get('function')->getData());

            // Si on est pas vendeur
            if (in_array($form->get('function')->getData(), ['freemium', 'premium'])) {
                // Company champ libre
                $user->setCompany($form->get('company')->getData());
            } else {
                // Il va falloir créer une entreprise
                $user->setBusinessAddress(null);

                $company = new Company();
                $company->setName($form->get('company')->getData());
                $company->setBusinessAddress($form->get('companyAddress')->get('businessAddress')->getData());
                // ajout des champs city et postalCode
                $company->setPostalCode($form->get('companyAddress')->get('postalCode')->getData());
                $company->setCity($form->get('companyAddress')->get('city')->getData());
                $company->setSiretNumber($form->get('siretNumber')->getData());
                $company->setVatNumber($form->get('vatNumber')->getData());
                $company->setOwner($user);
                $entityManager->persist($company);

                $user->setVendorCompany($company);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $templatedEmail = (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject($translator->trans('mailer.register.subject', [], 'messages'))
                ->htmlTemplate('registration/confirmation_email.html.twig');

            if (null !== $this->senderAddress) {
                $templatedEmail->from($this->senderAddress);
            }

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $templatedEmail
            );

            $security->login($user, 'form_login', 'main');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route(path: '/verify/email',
        name: 'app_verify_email',
    )]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_index');
    }
}
