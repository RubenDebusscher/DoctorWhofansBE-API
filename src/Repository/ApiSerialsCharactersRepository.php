<?php

namespace App\Repository;

use App\Entity\ApiSerialsCharacters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiSerialsCharacters>
 */
class ApiSerialsCharactersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSerialsCharacters::class);
    }
}