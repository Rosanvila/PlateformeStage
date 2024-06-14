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
use App\Entity\Post;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[AsLiveComponent(template: 'components/feed/PostItem.html.twig')]
class PostItem extends AbstractController
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    private const PER_PAGE = 10;

    #[LiveProp]
    public Post $item;

    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[ExposeInTemplate]
    public function likesCount(): int
    {
        return $this->item->getLikes()->count();
    }

    #[ExposeInTemplate]
    public function commentsCount(): int
    {
        return $this->item->getComments()->count();
    }

    #[ExposeInTemplate]
    public function isLikedByUser(): bool
    {
        return $this->item->getLikes()->contains($this->security->getUser());
    }

    #[LiveAction]
    public function like(): void
    {
        if($this->item->getLikes()->contains($this->security->getUser())) {
            $this->item->getLikes()->removeElement($this->security->getUser());
        } else {
            $this->item->getLikes()->add($this->security->getUser());
        }

        $this->entityManager->persist($this->item);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function delete(): RedirectResponse
    {
        foreach($this->item->getPostMedias() as $media) {
            $this->entityManager->remove($media);
        }
        
        $this->entityManager->remove($this->item);
        $this->entityManager->flush();

        $this->emit('post:deleted', [
            'post' => $this->item->getId(),
        ]);

        return $this->redirectToRoute('app_index');
    }

}
