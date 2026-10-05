<?php

namespace App\Repository;

use App\Entity\V3AttributeValidationRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V3AttributeValidationRules>
 */
class V3AttributeValidationRulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V3AttributeValidationRules::class);
    }
}