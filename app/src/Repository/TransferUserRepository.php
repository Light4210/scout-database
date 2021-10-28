<?php

namespace App\Repository;

use App\Entity\TransferUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransferUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransferUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransferUser[]    findAll()
 * @method TransferUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransferUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransferUser::class);
    }

    // /**
    //  * @return TransferUser[] Returns an array of TransferUser objects
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
    public function findOneBySomeField($value): ?TransferUser
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
