<?php

namespace App\Repository;

use App\Entity\CancelEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CancelEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CancelEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CancelEvent[]    findAll()
 * @method CancelEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CancelEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CancelEvent::class);
    }

    // /**
    //  * @return CancelEvent[] Returns an array of CancelEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CancelEvent
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
