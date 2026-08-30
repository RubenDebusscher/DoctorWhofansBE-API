<?php

namespace App\Repository;

use App\Entity\ApiLookupConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiLookupConfig>
 */
class ApiLookupConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiLookupConfig::class);
    }

    /**
     * Zoek snel de configuratie op basis van het slug
     */
    public function findBySlug(string $slug): ?ApiLookupConfig
    {
        return $this->findOneBy(['entitySlug' => strtolower($slug)]);
    }
}
