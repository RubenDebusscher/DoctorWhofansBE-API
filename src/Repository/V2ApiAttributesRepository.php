<?php

namespace App\Repository;

use App\Entity\V2ApiAttributes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V2ApiAttributes>
 */
class V2ApiAttributesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V2ApiAttributes::class);
    }
}