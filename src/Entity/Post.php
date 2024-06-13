<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creationDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $editDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'postsLikes')]
    private Collection $likes;

    /**
     * @var Collection<int, PostComment>
     */
    #[ORM\OneToMany(targetEntity: PostComment::class, mappedBy: 'post')]
    private Collection $postsComments;

    /**
     * @var Collection<int, PostMedia>
     */
    #[ORM\OneToMany(targetEntity: PostMedia::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $postMedias;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->postsComments = new ArrayCollection();
        $this->postMedias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getEditDate(): ?\DateTimeImmutable
    {
        return $this->editDate;
    }

    public function setEditDate(?\DateTimeImmutable $editDate): static
    {
        $this->editDate = $editDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(User $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        }

        return $this;
    }

    public function removeLike(User $like): static
    {
        $this->likes->removeElement($like);

        return $this;
    }

    /**
     * @return Collection<int, PostComment>
     */
    public function getComments(): Collection
    {
        return $this->postsComments;
    }

    public function addComment(PostComment $comment): static
    {
        if (!$this->postsComments->contains($comment)) {
            $this->postsComments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(PostComment $comment): static
    {
        if ($this->postsComments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostMedia>
     */
    public function getPostMedias(): Collection
    {
        return $this->postMedias;
    }

    public function addPostMedia(PostMedia $postMedia): static
    {
        if (!$this->postMedias->contains($postMedia)) {
            $this->postMedias->add($postMedia);
            $postMedia->setPost($this);
        }

        return $this;
    }

    public function removePostMedia(PostMedia $postMedia): static
    {
        if ($this->postMedias->removeElement($postMedia)) {
            // set the owning side to null (unless already changed)
            if ($postMedia->getPost() === $this) {
                $postMedia->setPost(null);
            }
        }

        return $this;
    }
}
