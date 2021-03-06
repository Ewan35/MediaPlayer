<?php

namespace App\Repository;

use App\Entity\IsAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IsAdmin|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsAdmin|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsAdmin[]    findAll()
 * @method IsAdmin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsAdminRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IsAdmin::class);
    }

    // /**
    //  * @return IsAdmin[] Returns an array of IsAdmin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IsAdmin
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
