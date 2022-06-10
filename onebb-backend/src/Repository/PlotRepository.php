<?php

namespace App\Repository;

use App\Entity\Plot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plot[]    findAll()
 * @method Plot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plot::class);
    }

    // /**
    //  * @return Plot[] Returns an array of Plot objects
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
    public function findOneBySomeField($value): ?Plot
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
