<?php

namespace App\Repository;

use App\Entity\V2ApiTemplates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V2ApiTemplates>
 */
class V2ApiTemplatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V2ApiTemplates::class);
    }
}