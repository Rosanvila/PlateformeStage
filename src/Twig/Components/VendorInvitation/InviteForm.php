<?php

namespace App\Twig\Components\VendorInvitation;

use App\Entity\Invitation;
use App\Form\InviteVendorType;
use App\Service\Mailer\InviteMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent(template: 'components/vendorInvitation/InviteForm.html.twig')]
class InviteForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    private InviteMailer $inviteMailer;
    private Security $security;
    private EntityManagerInterface $entityManager;

    #[LiveProp]
    public ?Invitation $initialFormData = null;

    public function __construct(InviteMailer $inviteMailer, Security $security, EntityManagerInterface $entityManager)
    {
        $this->inviteMailer = $inviteMailer;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(InviteVendorType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function addInvite(EntityManagerInterface $entityManager): RedirectResponse
    {
        $this->submitForm();

        /** @var Invitation $invitation */
        $invitation = $this->getForm()->getData();

        $user = $this->security->getUser();
        if (null === $user) {
            throw new \RuntimeException('Unable to send invitation email');
        }

        // Configuration de l'invitation
        $invitation->setSender($user);
        $invitation->setCompany($user->getVendorCompany());
        $invitation->setUuid(Uuid::v7()->toRfc4122());
        $invitation->setStatus('pending');
        $invitation->setSentAt(new \DateTime());

        // Enregistrement dans la base de données
        $entityManager->persist($invitation);
        $entityManager->flush();

        // Envoi de l'email d'invitation
        try {
            $this->inviteMailer->sendInvitation($invitation);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to send invitation email', 0, $e);
        }

        return $this->redirectToRoute('app_preferences');
    }
}
