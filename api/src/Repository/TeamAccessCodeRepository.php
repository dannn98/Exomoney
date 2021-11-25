<?php

namespace App\Repository;

use App\Entity\TeamAccessCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamAccessCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamAccessCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamAccessCode[]    findAll()
 * @method TeamAccessCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamAccessCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamAccessCode::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(TeamAccessCode $teamAccessCode)
    {
        $this->getEntityManager()->persist($teamAccessCode);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(Collection $teamAccessCodes)
    {
        foreach ($teamAccessCodes as $teamAccessCode) {
            $this->getEntityManager()->remove($teamAccessCode);
        }
        $this->getEntityManager()->flush();
    }

    // /**
    //  * @return TeamAccessCode[] Returns an array of TeamAccessCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamAccessCode
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
