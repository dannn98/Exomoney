<?php

namespace App\Repository;

use App\Entity\Repayment;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Repayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repayment[]    findAll()
 * @method Repayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repayment::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Repayment $repayment)
    {
        $this->getEntityManager()->persist($repayment);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ORMException
     */
    public function saveCollection(array $repaymentCollection)
    {
        foreach ($repaymentCollection as $repayment) {
            $this->getEntityManager()->persist($repayment);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function removeCollection(array $repaymentCollection)
    {
        foreach ($repaymentCollection as $repayment) {
            $this->getEntityManager()->remove($repayment);
        }
        $this->getEntityManager()->flush();
    }

    public function removeAllFromTeam(Team $team)
    {
        $qb = $this->createQueryBuilder('delete');
        $qb
            ->delete(Repayment::class, 'r')
            ->where('r.team = :team')
            ->setParameter('team', $team);

        $qb->getQuery()->execute();
    }

    // /**
    //  * @return Repayment[] Returns an array of Repayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Repayment
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
