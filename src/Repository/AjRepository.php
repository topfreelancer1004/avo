<?php

namespace App\Repository;

use App\Entity\Aj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aj|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aj|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aj[]    findAll()
 * @method Aj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AjRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aj::class);
    }

    // /**
    //  * @return Aj[] Returns an array of Aj objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Aj
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
