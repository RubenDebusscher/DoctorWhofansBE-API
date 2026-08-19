<?php

namespace App\Repository;

use App\Entity\LPaginaTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LPaginaTypes>
 */
class LPaginaTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LPaginaTypes::class);
    }
}