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

use App\Entity\Post;
use App\Entity\PostMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponder;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;

#[AsLiveComponent(template: 'components/feed/FormCreatePost.html.twig')]
class FormCreatePost extends AbstractController
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    #[NotBlank, NotNull]
    public string $postContent = '';

    #[LiveProp(writable: true)]
    public array $postMedias = [];

    #[LiveAction]
    public function savePost(EntityManagerInterface $entityManager, LiveResponder $liveResponder): void
    {
        $this->validate();

        $post = new Post();
        $post->setContent($this->postContent);
        $post->setAuthor($this->getUser());
        $post->setCreationDate(new \DateTimeImmutable());
        
        // On gère les médias liés
        foreach ($this->postMedias as $base64Content) {
            $media = new PostMedia();
            $media->setContent($base64Content);
            $media->setPost($post);
            $entityManager->persist($media);
        }

        $entityManager->persist($post);
        $entityManager->flush();

        $this->dispatchBrowserEvent('modal:close');
        $this->emit('post:created', [
            'post' => $post->getId(),
        ]);

        // reset the fields in case the modal is opened again
        $this->postContent = '';
        $this->resetValidation();
    }

    #[LiveListener('base64FilesAdded')]
    public function handleMedias(#[LiveArg] array $base64FilesArray)
    {
        $this->postMedias = $base64FilesArray;
    }
}
