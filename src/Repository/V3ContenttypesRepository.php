<?php

namespace App\Repository;

use App\Entity\V3Contenttypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V3Contenttypes>
 */
class V3ContenttypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V3Contenttypes::class);
    }
}