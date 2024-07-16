<?php
namespace App\Twig\Components\VendorInvitation;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent (template: 'components/vendorInvitation/accountCounter.html.twig')]
class AccountCounter
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $availableAccounts = 5;

    public function addAccount(): void
    {
        // Logic for adding an account via a payment system

        $this->availableAccounts++;
    }
}
