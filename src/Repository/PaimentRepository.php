<?php

namespace App\Repository;

use App\Entity\Paiment;
use App\Entity\Procedure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Paiment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiment[]    findAll()
 * @method Paiment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiment::class);
    }

    public function getTotalEarnings($user){
        $qb = $this->createQueryBuilder('p');
        return $qb->select('SUM(p.amount)')
                  ->andWhere('p.user = :user')
                  ->setParameter("user", $user)
                  ->getQuery()
                  ->getSingleScalarResult();
    }

    public function getTotalPerMonth($user) {
        $qb = $this->createQueryBuilder('p');
        return $qb->select('SUM(p.amount)')
                  ->andWhere('p.user = :user')
                  ->setParameter("user", $user)
                  ->andWhere('p.created_at >= :created_at')
                  ->setParameter("created_at", \DateTime::createFromFormat('Y-m-d', date('Y-m-01')))
                  ->getQuery()
                  ->getSingleScalarResult();

    }

    public function getBestProcedure($user): ?Paiment {

        $qb = $this->createQueryBuilder('p');
        $ret = $qb->select('p, SUM(p.amount) total')
                  ->andWhere('p.user = :user')
                  ->setParameter("user", $user)
                  ->groupBy('p.procedur')
                  ->orderBy('total', 'DESC')
                  ->getQuery()
                  ->setMaxResults(1)
                  ->getOneOrNullResult();


        return $ret!=null ? $ret[0] : null;

    }

    public function getGeneralReport($user){
        $qb = $this->createQueryBuilder('p');
        $ret = $qb->select('p as pmnt, pr, MONTH(p.created_at) as m, (p.procedur) as pid, SUM(p.amount) as amnt')
                ->join('p.procedur', 'pr')
                ->andWhere('p.user = :user')
                ->setParameter("user", $user)
                ->groupBy('m, pid')
                ->getQuery()
                ->getResult();
        return $ret;
    }

    public function getGeneralReportTotals($user){
        $qb = $this->createQueryBuilder('p');
        $ret = $qb->select('p as pmnt, MONTH(p.created_at) as m, SUM(p.amount) as amnt')
                  ->andWhere('p.user = :user')
                  ->setParameter("user", $user)
                  ->groupBy('m')
                  ->orderBy('m')
                  ->getQuery()
                  ->getResult();
        return $ret;
    }
    public function getReportByProcedure($user, $procedure) {
        $qb = $this->createQueryBuilder('p');
        $ret = $qb->select("p as pmnt, c as client, SUM(p.amount) as amnt, MONTH(p.created_at) as m, (p.client) as cid")
                  ->join('p.client', 'c')
                  ->andWhere('p.user = :user')
                  ->andWhere('p.procedur = :procedure')
                  ->setParameters(['user'=>$user, 'procedure'=>$procedure])
                  ->groupBy('p.client, m')
                  ->getQuery()
                  ->getResult()
        ;
        return $ret;
    }
    // /**
    //  * @return Paiment[] Returns an array of Paiment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Paiment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
