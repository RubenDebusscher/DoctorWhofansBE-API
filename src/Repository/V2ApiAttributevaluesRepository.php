<?php

namespace App\Repository;

use App\Entity\V2ApiAttributevalues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V2ApiAttributevalues>
 */
class V2ApiAttributevaluesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V2ApiAttributevalues::class);
    }
}