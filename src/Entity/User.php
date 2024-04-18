<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use danielburger1337\SchebTwoFactorBundle\Model\TwoFactorEmailInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, TwoFactorEmailInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $email;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $authCode;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dh_insert;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dh_update;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $email_auth_code_expires_at;

    public function __toString(): string
    {
        return (string) $this->email;
    }

    #[ORM\PrePersist]
    public function setDhInsertValue(): void
    {
        $this->setDhInsert(new \DateTime('now'));
    }

    #[ORM\PreUpdate]
    public function setDhUpdateValue(): void
    {
        $this->setDhUpdate(new \DateTime('now'));
    }

    public function eraseCredentials(){

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }
    
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true; // This can be a persisted field to switch email code authentication on/off
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function getDhInsert(): ?\DateTimeInterface
    {
        return $this->dh_insert;
    }

    public function setDhInsert(?\DateTimeInterface $dh_insert): self
    {
        $this->dh_insert = $dh_insert;

        return $this;
    }

    public function getDhUpdate(): ?\DateTimeInterface
    {
        return $this->dh_update;
    }

    public function setDhUpdate(?\DateTimeInterface $dh_update): self
    {
        $this->dh_update = $dh_update;

        return $this;
    }
    public function getEmailAuthCodeExpiresAt(): \DateTimeImmutable|null{
        return new \DateTimeImmutable($this->email_auth_code_expires_at->format('Y-m-d H:i:s'));
    }

    public function setEmailAuthCodeExpiresAt(\DateTimeImmutable $expiresAt): void{
        $this->email_auth_code_expires_at = $expiresAt;
    }
}