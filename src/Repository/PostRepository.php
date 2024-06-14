<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

       public function queryFeedForUser(User $user, bool $isCount=false): Query
       {
            $query = $this->createQueryBuilder('p')
                        ->andWhere('p.author = :user')
                        ->setParameter('user', $user);
            if ($isCount) {
                $query = $query->select('count(p.id)');
            }
            else
            {
                $query = $query->orderBy('p.creationDate', 'DESC');
            }

            $query = $query->getQuery();

           return $query;
       }
}
