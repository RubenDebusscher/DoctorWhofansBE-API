<?php
namespace App\Repository;

use App\Entity\Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Code>
 */
class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    /**
     * Haalt alle actieve en niet-verstreken codes op voor een specifieke groep
     *
     * @return Code[]
     */
    public function findActiveByGroupKey(string $groupKey): array
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('c')
            ->join('c.codeGroup', 'g')
            ->where('g.codeKey = :groupKey')
            ->andWhere('c.isActive = :active')
            ->andWhere('c.validUntil IS NULL OR c.validUntil > :now')
            ->setParameter('groupKey', $groupKey)
            ->setParameter('active', true)
            ->setParameter('now', $now)
            ->orderBy('c.displayOrder', 'ASC')
            ->addOrderBy('c.label', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
