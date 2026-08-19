<?php

namespace App\Repository;

use App\Entity\ApiSerialsDoctors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiSerialsDoctors>
 */
class ApiSerialsDoctorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSerialsDoctors::class);
    }
}