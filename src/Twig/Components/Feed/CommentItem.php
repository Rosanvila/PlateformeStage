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
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent(template: 'components/feed/CommentItem.html.twig')]
class CommentItem
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    private const PER_PAGE = 10;

    #[LiveProp]
    public PostComment $item;

    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

}
