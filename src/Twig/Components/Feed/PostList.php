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

#[AsLiveComponent(template: 'components/feed/PostList.html.twig')]
class PostList
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    private const PER_PAGE = 10;

    #[LiveProp]
    public int $page = 1;

    /**
     * @var Post[]
     */
    #[LiveProp]
    public array $newItems = [];

    public function __construct(private readonly PostService $postService, private readonly Security $security)
    {
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }

    public function hasMore(): bool
    {
        return $this->postService->countUserFeed($this->security->getUser()) > ($this->page * self::PER_PAGE);
    }

    #[ExposeInTemplate]
    public function getItems(): Iterable
    {
        $posts = $this->postService->getUserFeed($this->security->getUser(), $this->page, self::PER_PAGE);

        return $posts->getItems();
    }

    #[LiveListener('post:created')]
    public function onPostCreated(#[LiveArg] Post $post): void
    {
        array_unshift($this->newItems,$post);
    }

    #[ExposeInTemplate('per_page')]
    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

}
