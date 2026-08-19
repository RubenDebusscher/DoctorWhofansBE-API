<?php

namespace App\Repository;

use App\Entity\ApiSerialsCharactersactors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiSerialsCharactersactors>
 */
class ApiSerialsCharactersactorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSerialsCharactersactors::class);
    }
}