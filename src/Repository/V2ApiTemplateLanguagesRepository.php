<?php

namespace App\Repository;

use App\Entity\V2ApiTemplateLanguages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V2ApiTemplateLanguages>
 */
class V2ApiTemplateLanguagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V2ApiTemplateLanguages::class);
    }
}