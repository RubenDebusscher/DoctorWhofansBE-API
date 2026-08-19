<?php

namespace App\Repository;

use App\Entity\V3Attributevalidationrules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V3Attributevalidationrules>
 */
class V3AttributevalidationrulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V3Attributevalidationrules::class);
    }
}