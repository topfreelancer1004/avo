<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getClientsForCurrentMonth($user) {
        $qb = $this->createQueryBuilder("c");
        return $qb->select($qb->expr()->count('c.id'))
                   ->andWhere('c.user = :user')
                   ->setParameter("user", $user)
                   ->andWhere('c.created_at >= :created_at')
                   ->setParameter("created_at", \DateTime::createFromFormat('Y-m-d', date('Y-m-01')))
                   ->getQuery()
                   ->getSingleScalarResult()
            ;
    }

    public function getAverageClients($user) {
        $qb = $this->createQueryBuilder('c');
        $totalUsers =  $this->getClientsCount($user);
        $monthUsed = $qb->select($qb->expr()->countDistinct("MONTH(c.created_at)"))
                     ->where('c.user = :user')
                     ->setParameter("user", $user)
                     ->getQuery()
                     ->getSingleScalarResult();
        if ($monthUsed ==0) {
            return 0;
        }
        return intval($totalUsers/$monthUsed);
    }

    public function getClientsCount($user) {
        $qb = $this->createQueryBuilder('c');
        return $qb->select($qb->expr()->count('c.id'))
                          ->where('c.user = :user')
                          ->setParameter("user", $user)
                          ->getQuery()
                          ->getSingleScalarResult();
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
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
    public function findOneBySomeField($value): ?Client
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
