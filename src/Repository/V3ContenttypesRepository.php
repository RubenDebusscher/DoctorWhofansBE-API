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

    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.attributeContentTypes', 'act') // Join met de koppelentiteit
            ->leftJoin('act.attribute', 'a')             // Join met het attribuut zelf
            ->addSelect('act', 'a')                       // Laad alles in 1 query (Eager loading)
            ->getQuery()
            ->getResult();
    }
}
