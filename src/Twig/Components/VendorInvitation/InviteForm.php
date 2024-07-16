<?php

namespace App\Twig\Components\VendorInvitation;

use App\Entity\Company;
use App\Entity\Invitation;
use App\Service\Mailer\InviteMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\HttpFoundation\Request;

#[AsLiveComponent (template: 'components/vendorInvitation/inviteForm.html.twig')]
class InviteForm
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $inviteEmail = '';

    private InviteMailer $mailer;
    private EntityManagerInterface $entityManager;

    public function __construct(InviteMailer $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function invite(Request $request): void
    {
        $email = $request->request->get('invite_email');

        // Generate unique token
        $token = uniqid();

        // Get the company from the database
        $company = $this->entityManager->getRepository(Company::class)->find();

        // Create and save the invitation entity
        $invitation = new Invitation();
        $invitation->setSender($company->getOwner());
        $invitation->setToken($token);
        $invitation->setCompany($company);
        $invitation->setStatus('En attente');
        $invitation->setSentAt(new \DateTime());

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        // Send the invitation email
        $this->mailer->sendInvitation($email, $token, 'Your Company Name');

        // Optionally, you can flash a success message or handle errors
    }
}
