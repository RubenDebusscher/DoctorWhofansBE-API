<?php

namespace App\Repository;

use App\Entity\ManagementPages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ManagementPages>
 */
class ManagementPagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManagementPages::class);
    }

    public function findPendingApiIntegrations(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.apiItem IS NULL')
            ->andWhere('p.ignoreApi = :ignore')
            ->setParameter('ignore', false)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
