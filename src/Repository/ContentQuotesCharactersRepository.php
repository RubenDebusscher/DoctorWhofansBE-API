<?php

namespace App\Repository;

use App\Entity\ContentQuotesCharacters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContentQuotesCharacters>
 */
class ContentQuotesCharactersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentQuotesCharacters::class);
    }
}