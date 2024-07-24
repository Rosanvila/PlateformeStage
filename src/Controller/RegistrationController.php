<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\InvitationRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\RegisterInvitation\PreFillFields;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RegistrationController extends AbstractController
{
    private Address|string|null $senderAddress;
    private PreFillFields $preFillFields;

    public function __construct(
        PreFillFields                                                      $preFillFields,
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
        $this->preFillFields = $preFillFields;
    }

    #[Route(
        path: '/register',
        name: 'app_register',
    )]
    public function register(): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route(path: '/register/{token}',
        name: 'app_register_with_invitation',
    )]
    public function registerWithInvitation(string $token, InvitationRepository $invitationRepository, EntityManagerInterface $entityManager): Response
    {
        $invitation = $invitationRepository->findOneBy(['uuid' => $token]);

        // Check if the invitation exists
        if (!$invitation) {
            throw $this->createNotFoundException('Invitation not found');
        }

        // Check if the invitation has already been accepted
        if ($invitation->getIsAccepted() === true) {
            throw $this->createAccessDeniedException('Invitation already accepted');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $this->preFillFields->invitationFillField($form, $invitation);

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'invitation' => $invitation,
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
