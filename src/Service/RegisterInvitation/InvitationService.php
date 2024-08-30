<?php

namespace App\Service\RegisterInvitation;

use App\Entity\Invitation;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class InvitationService
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
    }

    public function getInvitationFromSession(): ?Invitation
    {
        $session = $this->requestStack->getSession();
        return $session->get('invitation');
    }

    public function setInvitationToSession(Invitation $invitation): void
    {
        $session = $this->requestStack->getSession();
        $session->set('invitation', $invitation);
    }

    public function clearInvitationFromSession(): void
    {
        $session = $this->requestStack->getSession();
        $session->remove('invitation');
    }
}
