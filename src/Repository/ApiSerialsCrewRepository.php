<?php

namespace App\Repository;

use App\Entity\ApiSerialsCrew;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiSerialsCrew>
 */
class ApiSerialsCrewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSerialsCrew::class);
    }
}