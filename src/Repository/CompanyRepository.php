<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function findSearchResults(string $queryStr, int $limit = null): array
    {
        $queryStr = strtolower($queryStr);
        $qb = $this->createQueryBuilder('c')
            ->where('LOWER(c.name) LIKE :queryStr')
            ->setParameter('queryStr', '%'.$queryStr.'%')
            ->orderBy('c.name', 'ASC');

        if(!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findOneByName(string $name): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
