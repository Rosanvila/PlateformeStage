<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private const DEFAULT_PHOTO = "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";

    #[ORM\Id]
    #[ORM\GeneratedValue]
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

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: "string")]
    private ?string $firstname;

    #[ORM\Column(type: "string")]
    private ?string $lastname;

    #[ORM\Column(nullable: true, type: "text", length: 65535)]
    private ?string $photo = self::DEFAULT_PHOTO;

    #[ORM\Column(nullable: true)]
    private ?string $job;

    #[ORM\Column(type: "array", nullable: true)]
    private ?array $expertise;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $businessAddress;
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $postalCode;
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $city;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $company;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $language;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Company $vendorCompany = null;

    /**
     * @var Collection<int, Company>
     */
    #[ORM\OneToMany(targetEntity: Company::class, mappedBy: 'owner')]
    private Collection $companies;
    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author')]
    private Collection $posts;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'likes')]
    private Collection $postsLikes;

    /**
     * @var Collection<int, PostComment>
     */
    #[ORM\OneToMany(targetEntity: PostComment::class, mappedBy: 'author')]
    private Collection $postsComments;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
    private Collection $follows;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follows')]
    private Collection $followers;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->postsLikes = new ArrayCollection();
        $this->postsComments = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->email;
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

    public function eraseCredentials()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

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
        return (string)$this->email;
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

    public function getEmailAuthCodeExpiresAt(): \DateTimeImmutable|null
    {
        return new \DateTimeImmutable($this->email_auth_code_expires_at->format('Y-m-d H:i:s'));
    }

    public function setEmailAuthCodeExpiresAt(\DateTimeImmutable $expiresAt): void
    {
        $this->email_auth_code_expires_at = $expiresAt;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoto(): ?string
    {
        if (!is_null($this->photo)) {
            return $this->photo;
        } else {
            return self::DEFAULT_PHOTO;
        }
    }

    public function getSrcPhoto(): ?string
    {
        return "data:image/jpeg;base64," . $this->getPhoto();
    }

    public function setPhoto(?string $photo): self
    {
        if (!is_null($photo)) {
            $this->photo = $photo;
        } else {
            $this->photo = self::DEFAULT_PHOTO;
        }
        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getExpertise(): ?array
    {
        return $this->expertise;
    }

    public function setExpertise(?array $expertise): void
    {
        $this->expertise = $expertise;
    }

    public function getBusinessAddress(): ?string
    {
        return $this->businessAddress;
    }

    public function setBusinessAddress(?string $businessAddress): void
    {
        $this->businessAddress = $businessAddress;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): void
    {
        $this->company = $company;
    }

    public function getVendorCompany(): ?Company
    {
        return $this->vendorCompany;
    }

    public function setVendorCompany(?Company $vendorCompany): static
    {
        $this->vendorCompany = $vendorCompany;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setOwner($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getOwner() === $this) {
                $company->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsLikes(): Collection
    {
        return $this->postsLikes;
    }

    public function addPostsLike(Post $postsLike): static
    {
        if (!$this->postsLikes->contains($postsLike)) {
            $this->postsLikes->add($postsLike);
            $postsLike->addLike($this);
        }

        return $this;
    }

    public function removePostsLike(Post $postsLike): static
    {
        if ($this->postsLikes->removeElement($postsLike)) {
            $postsLike->removeLike($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PostComment>
     */
    public function getPostsComments(): Collection
    {
        return $this->postsComments;
    }

    public function addPostsComment(PostComment $postsComment): static
    {
        if (!$this->postsComments->contains($postsComment)) {
            $this->postsComments->add($postsComment);
            $postsComment->setAuthor($this);
        }

        return $this;
    }

    public function removePostsComment(PostComment $postsComment): static
    {
        if ($this->postsComments->removeElement($postsComment)) {
            // set the owning side to null (unless already changed)
            if ($postsComment->getAuthor() === $this) {
                $postsComment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(self $follow): static
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
        }

        return $this;
    }

    public function removeFollow(self $follow): static
    {
        $this->follows->removeElement($follow);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(self $follower): static
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->addFollow($this);
        }

        return $this;
    }

    public function removeFollower(self $follower): static
    {
        if ($this->followers->removeElement($follower)) {
            $follower->removeFollow($this);
        }

        return $this;
    }
}