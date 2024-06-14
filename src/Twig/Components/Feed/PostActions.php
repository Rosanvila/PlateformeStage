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
use App\Entity\PostComment;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[AsLiveComponent(template: 'components/feed/PostActions.html.twig')]
class PostActions extends AbstractController
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    private const PER_PAGE = 10;

    #[LiveProp]
    public ?Post $post = null;

    #[LiveProp]
    public ?PostComment $comment = null;

    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[LiveAction]
    public function delete(): RedirectResponse
    {
        // Si entité Post
        if($this->post) {
            foreach($this->post->getPostMedias() as $media) {
                $this->entityManager->remove($media);
            }
            
            $this->entityManager->remove($this->post);
            $this->entityManager->flush();

            $this->emit('post:deleted', [
                'post' => $this->post->getId(),
            ]);
        }
        // Si entité Comment
        else if ($this->comment) {
            $this->entityManager->remove($this->comment);
            $this->entityManager->flush();

            $this->emit('comment:deleted', [
                'comment' => $this->comment->getId(),
            ]);
        }

        return $this->redirectToRoute('app_index');
    }

}
