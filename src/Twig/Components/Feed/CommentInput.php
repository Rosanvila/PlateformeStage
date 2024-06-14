<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Components\Feed;

use App\Service\Feed\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\PostComment;
use App\Entity\Post;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\Component\Validator\Constraints\NotBlank;

#[AsLiveComponent(template: 'components/feed/CommentInput.html.twig')]
class CommentInput
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    #[NotBlank]
    public string $content = '';

    #[LiveProp(writable: true)]
    public Post $post;

    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[LiveAction]
    public function saveComment(): void
    {
        $this->validate();

        $comment = new PostComment();
        $comment->setContent($this->content);
        $comment->setAuthor($this->security->getUser());
        $comment->setCreationDate(new \DateTimeImmutable());
        $comment->setPost($this->post);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $this->emit('comment:created', [
            'comment' => $comment->getId(),
        ]);

        // reset the fields in case the modal is opened again
        $this->content = '';
        $this->resetValidation();
    }



}
