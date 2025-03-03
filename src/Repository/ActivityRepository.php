<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    //    /**
    //     * @return Activity[] Returns an array of Activity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Activity
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    
    public function searchByCriteria(array $criteria)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.ratings', 'r') // Jointure avec la table Rating
            ->addSelect('AVG(r.score) as avgRating') // Calcul de la moyenne des notes
            ->leftJoin('e.partners', 'p') // Jointure avec Partner
            ->groupBy('e.id') // Groupement par événement
        ;
        
        if (!empty($criteria['tags'])) {
            $qb->join('e.tags', 't')
                ->andWhere('t.id IN (:tags)')
                ->setParameter('tags', $criteria['tags']);
        }

        // 📌 Vérification que 'region' et 'city' proviennent bien de `partner`
        if (!empty($criteria['region'])) {
            $qb->andWhere('p.region LIKE :region') // Utilisation de 'p.region' au lieu de 'e.region'
                ->setParameter('region', '%'.$criteria['region'].'%');
        }

        if (!empty($criteria['city'])) {
            $qb->andWhere('p.city LIKE :city') // Utilisation de 'p.city' au lieu de 'e.city'
                ->setParameter('city', '%'.$criteria['city'].'%');
        }

        if (!empty($criteria['price'])) {
            $qb->andWhere('e.price BETWEEN 0 AND :price')
                ->setParameter('price', $criteria['price']);
        }

        // Filtrer par note minimale
        if (!empty($criteria['score'])) {
            $qb->having('avgRating >= :score')
            ->setParameter('score', $criteria['score']);
        }

        return array_map(fn($row) => $row[0], $qb->getQuery()->getResult());
    }
}