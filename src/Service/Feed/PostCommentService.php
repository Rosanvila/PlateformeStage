<?php
namespace App\Service\Feed;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\PostComment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PostCommentService
{

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly PaginatorInterface $paginator)
    {
    }

    public function getComments(Post $post, int $page = 1, int $limitPerPage = 10): PaginationInterface
    {
        $commentsQuery = $this->entityManager->getRepository(PostComment::class)->findBy(["post" => $post], ["creationDate" => "DESC"]);

        $posts = $this->paginator->paginate($commentsQuery, $page, $limitPerPage);

        return $posts;
    }

    public function countComments(Post $post): int
    {
        $postsCount = $this->entityManager->getRepository(PostComment::class)->count(["post" => $post]);

        return $postsCount;
    }
}