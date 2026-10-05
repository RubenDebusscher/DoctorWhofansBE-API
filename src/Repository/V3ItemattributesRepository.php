<?php

namespace App\Repository;

use App\Entity\V3Itemattributes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<V3Itemattributes>
 */
class V3ItemattributesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, V3Itemattributes::class);
    }
    /**
     * Telt het aantal unieke child-items dat via lookupvalue gekoppeld is aan de parent.
     */
    public function countChildrenByLookupValue(int $parentId): int
    {
        return (int) $this->createQueryBuilder('ia')
            ->select('COUNT(DISTINCT ia.item)')
            ->join('ia.attributeid', 'a')
            ->where('ia.lookupvalue = :parentId')
            ->andWhere('UPPER(a.name) LIKE :parentKeyword')
            ->setParameter('parentId', $parentId)
            ->setParameter('parentKeyword', '%PARENT%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countChildrenByParentAttribute(int $parentId, string $attributeName): int
    {
        return (int) $this->createQueryBuilder('ia')
            ->select('COUNT(DISTINCT ia.item)')
            ->join('ia.attributeid', 'a')
            ->where('ia.lookupvalue = :parentId')
            ->andWhere('a.attributeid = :attrName')
            ->setParameter('parentId', $parentId)
            ->setParameter('attrName', $attributeName)
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * Telt de totale som op van een specifiek attribuut op alle child-items van een parent.
     */
    public function sumChildAttributeValue(int $parentId, int $targetAttributeId): int
    {
        // 1. Haal de item-IDs op van alle kinderen
        $childItemIds = $this->createQueryBuilder('ia')
            ->select('DISTINCT IDENTITY(ia.item)')
            ->where('ia.lookupvalue = :parentId')
            ->setParameter('parentId', $parentId)
            ->getQuery()
            ->getSingleColumnResult();

        if (empty($childItemIds)) {
            return 0;
        }

        // 2. Sommeer de waarden van het gewenste attribuut over deze kinderen
        $sum = $this->createQueryBuilder('ia2')
            ->select('SUM(CAST(ia2.numbervalue AS integer))')
            ->where('ia2.item IN (:childIds)')
            ->andWhere('ia2.attributeid = :targetAttrId')
            ->setParameter('childIds', $childItemIds)
            ->setParameter('targetAttrId', $targetAttributeId)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($sum ?? 0);
    }
}
