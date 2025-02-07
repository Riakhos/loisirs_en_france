<?php

namespace App\Repository;

use App\Entity\Exclusive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exclusive>
 */
class ExclusiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exclusive::class);
    }

    public function findWithActivities(int $id): ?Exclusive
    {
        $exclusive =  $this->createQueryBuilder('e')
            ->leftJoin('e.activities', 'a') // Jointure avec les activités
            ->addSelect('a') // Sélectionner aussi les activités pour éviter le lazy-loading
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

            // dump($exclusive);
            
            return $exclusive;
    }

    //    /**
    //     * @return Exclusive[] Returns an array of Exclusive objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exclusive
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}