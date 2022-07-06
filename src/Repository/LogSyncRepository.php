<?php

namespace App\Repository;

use App\Entity\LogSync;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LogSync>
 *
 * @method LogSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogSync[]    findAll()
 * @method LogSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogSyncRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogSync::class);
    }

    public function add(LogSync $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LogSync $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Count By Criteria
     *
     * @param Criteriaia $criteria
     * @return integer
     */
    public function countBy(Criteria $criteria):int
    {
        $persister = $this->getEntityManager()->getUnitOfWork()->getEntityPersister($this->_entityName);
        return $persister->count($criteria);
    }

//    /**
//     * @return LogSync[] Returns an array of LogSync objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LogSync
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
