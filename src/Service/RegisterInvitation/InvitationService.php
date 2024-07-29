<?php

namespace App\Service\RegisterInvitation;

use App\Entity\Invitation;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InvitationService
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
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
