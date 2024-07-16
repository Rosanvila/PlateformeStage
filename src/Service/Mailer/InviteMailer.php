<?php

namespace App\Service\Mailer;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class InviteMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendInvitation(string $email, string $token, string $companyName): void
    {
        $inviteUrl = 'https://mysecu.com/accept-invitation?token=' . $token;

        $email = (new Email())
            ->from('noreplyinvitation@mysecu.com')
            ->to($email)
            ->subject('Invitation to join ' . $companyName)
            ->html('<p>Click <a href="' . $inviteUrl . '">here</a> to join ' . $companyName . '</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Gérer l'exception ici, par exemple, enregistrer un message d'erreur dans un fichier log
            // ou notifier l'administrateur du système par un autre email ou un système de logging.
        }
    }
}