<?php

namespace App\Repository;

use App\Entity\PostMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostMedia>
 *
 * @method PostMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostMedia[]    findAll()
 * @method PostMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostMedia::class);
    }

    //    /**
    //     * @return PostMedia[] Returns an array of PostMedia objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PostMedia
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
