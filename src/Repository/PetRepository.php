<?php

namespace App\Repository;

use App\Entity\Pet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pet>
 */
class PetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pet::class);
    }
    
    public function findByFilters(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p');

        foreach ($filters as $index => $filter) {
            [$column, $operator, $value] = $filter;

            $paramName = "val_" . $index;

            $qb->andWhere("p.{$column} {$operator} :{$paramName}")
                ->setParameter($paramName, $value);
        }

        return $qb->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Pet[] Returns an array of Pet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Pet
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
