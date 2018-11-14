<?php

namespace App\Repository;

use App\Entity\Flayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Flayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flayer[]    findAll()
 * @method Flayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Flayer::class);
    }

    // /**
    //  * @return Flayer[] Returns an array of Flayer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Flayer
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
