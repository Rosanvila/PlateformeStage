<?php
namespace App\Service\Feed;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PostService
{

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly PaginatorInterface $paginator)
    {
    }

    public function getUserFeed(User $user, int $page = 1, int $limitPerPage = 10): PaginationInterface
    {
        $feedQuery = $this->entityManager->getRepository(Post::class)->queryFeedForUser($user);

        $posts = $this->paginator->paginate($feedQuery, $page, $limitPerPage);

        return $posts;
    }

    public function countUserFeed(User $user): int
    {
        $postsCount = $this->entityManager->getRepository(Post::class)->queryFeedForUser($user, true)->getSingleScalarResult();

        return $postsCount;
    }
}