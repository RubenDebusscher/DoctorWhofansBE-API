<?php

namespace App\Repository;

use App\Entity\ContentDownloadsLanguages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContentDownloadsLanguages>
 */
class ContentDownloadsLanguagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentDownloadsLanguages::class);
    }
}