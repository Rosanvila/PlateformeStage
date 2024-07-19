<?php declare(strict_types=1);

namespace App\Service\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Invitation;

final class InviteMailer
{
    private Address|string|null $senderAddress;

    public function __construct(
        private readonly TranslatorInterface                   $translator,
        private readonly MailerInterface                       $mailer,
        #[Autowire(env: 'AUTH_CODE_SENDER_EMAIL')] string|null $senderEmail,
        #[Autowire(env: 'AUTH_CODE_SENDER_NAME')] ?string      $senderName = null,
    )
    {
        if (null !== $senderEmail && null !== $senderName) {
            $this->senderAddress = new Address($senderEmail, $senderName);
        } elseif (null !== $senderEmail) {
            $this->senderAddress = $senderEmail;
        }
    }


    public function sendInvitation(Invitation $invitation): void
    {
        $message = new TemplatedEmail();
        $message
            ->to($invitation->getReceiverEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->html('security/email/authcode.html.twig')
            ;

        if (null !== $this->senderAddress) {
            $message->from($this->senderAddress);
        }

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to send email', 0, $e);
        }
    }
}
