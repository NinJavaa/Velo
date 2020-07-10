<?php

namespace App\Repository;

use App\Entity\EventConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventConfig[]    findAll()
 * @method EventConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventConfig::class);
    }

    // /**
    //  * @return EventConfig[] Returns an array of EventConfig objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventConfig
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
