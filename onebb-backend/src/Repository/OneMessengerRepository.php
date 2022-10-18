<?php

namespace App\Repository;

use App\Entity\OneMessenger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OneMessenger|null find($id, $lockMode = null, $lockVersion = null)
 * @method OneMessenger|null findOneBy(array $criteria, array $orderBy = null)
 * @method OneMessenger[]    findAll()
 * @method OneMessenger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OneMessengerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OneMessenger::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(OneMessenger $entity, bool $flush = true): void
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
    public function remove(OneMessenger $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    
    public function isExist($ids): ?OneMessenger
    {
        $qb = $this->createQueryBuilder("p");
        foreach ($ids as $id) {
            $qb->andWhere(":users_{$id->getId()} MEMBER OF p.users");
            $p[":users_{$id->getId()}"] = $id;
        }
        
        $qb->setParameters($p);
        
        return $qb->getQuery()->getOneOrNullResult();
    }


    // /**
    //  * @return OneMessenger[] Returns an array of OneMessenger objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OneMessenger
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
