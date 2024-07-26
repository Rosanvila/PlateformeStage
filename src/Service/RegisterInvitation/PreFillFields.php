<?php

namespace App\Service\RegisterInvitation;

use App\Entity\Invitation;
use Symfony\Component\Form\FormInterface;

class PreFillFields
{
    public function invitationFillField(FormInterface $form, Invitation $invitation): void
    {
        $company = $invitation->getCompany();
        $form->get('email')->setData($invitation->getReceiverEmail());
        $form->get('company')->get('companyField')->setData($company->getName());
        $form->get('function')->setData('vendor');
        $form->get('siretNumber')->setData($company->getSiretNumber());
        $form->get('vatNumber')->setData($company->getVatNumber());
        $form->get('businessAddress')->get('businessAddressField')->setData($company->getBusinessAddress());
        $form->get('postalCode')->get('postalCodeField')->setData($company->getPostalCode());
        $form->get('city')->get('cityField')->setData($company->getCity());
    }
}