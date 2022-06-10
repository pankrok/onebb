<?php

namespace App\Repository;

use App\Entity\SkinBoxes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinBoxes|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinBoxes|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinBoxes[]    findAll()
 * @method SkinBoxes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinBoxesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinBoxes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(SkinBoxes $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(SkinBoxes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return SkinBoxes[] Returns an array of SkinBoxes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SkinBoxes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
