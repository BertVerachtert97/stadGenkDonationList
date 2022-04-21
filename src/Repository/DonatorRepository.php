<?php

namespace App\Repository;

use App\Entity\Donator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Donator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donator[]    findAll()
 * @method Donator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donator::class);
    }

    public function getDonatorListByEnvironmentId(int $environmentId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.environmentId = :val')
            ->setParameter('val', $environmentId)
            ->getQuery()
            ->getResult()
            ;
    }
}
