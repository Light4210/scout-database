<?php

namespace App\Repository;

use App\Entity\Struct;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Struct|null find($id, $lockMode = null, $lockVersion = null)
 * @method Struct|null findOneBy(array $criteria, array $orderBy = null)
 * @method Struct[]    findAll()
 * @method Struct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Struct::class);
    }

   public function getDataForPromotion(string $structSlug){
       $query = $this->createQueryBuilder('s')->select('s.id', 's.name', 's.city')->where('s.type = :type')->setParameter('type', $structSlug);
       return $query->getQuery()->getResult();
   }
}
