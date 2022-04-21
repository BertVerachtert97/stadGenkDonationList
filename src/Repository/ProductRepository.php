<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductListByEnvironmentId(int $environmentId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.environmentId = :val')
            ->setParameter('val', $environmentId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getPublicProductListByEnvironmentId(int $environmentId)
    {
        return $this->createQueryBuilder('p')
            ->select('p , p.amount - p.donatedAmount as currentAmount')
            ->andWhere('p.environmentId = :val')
            ->andWhere('p.donatedAmount < p.amount')
            ->setParameter('val', $environmentId)
            ->getQuery()
            ->getResult()
            ;
    }
}
