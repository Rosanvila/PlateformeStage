<?php

namespace App\Twig\Components\VendorInvitation;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/vendorInvitation/invitationList.html.twig')]
class InvitationList
{
    use DefaultActionTrait;

    #[LiveProp]
    public array $invitations = [];

    public function mount(): void
    {
        // Logic to load the invitations from the database
        $this->invitations = [
            // Load invitations here
        ];
    }

    public function resend($invitationId)
    {
        // Logic to resend the invitation email
    }

    public function cancel($invitationId)
    {
        // Logic to cancel the invitation
    }

    public function deactivate($invitationId)
    {
        // Logic to deactivate the invitation
    }

    public function reactivate($invitationId)
    {
        // Logic to reactivate the invitation
    }
}