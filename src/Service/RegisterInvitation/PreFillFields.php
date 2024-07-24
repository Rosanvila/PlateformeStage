<?php

namespace App\Service\RegisterInvitation;

use App\Entity\Invitation;
use Symfony\Component\Form\FormInterface;

class PreFillFields
{

    public function invitationFillField(FormInterface $form, Invitation $invitation): void
    {

        $form->get('email')->setData($invitation->getReceiverEmail());
        $form->get('company')->get('companyField')->setData($invitation->getCompany()->getName());
        $form->get('function')->setData('vendor');
        $form->get('siretNumber')->setData($invitation->getCompany()->getSiretNumber());
        $form->get('vatNumber')->setData($invitation->getCompany()->getVatNumber());
    }
}