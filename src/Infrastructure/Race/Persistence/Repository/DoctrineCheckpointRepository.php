<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Repository\CheckpointRepositoryInterface;
use App\Infrastructure\Shared\Persistence\Repository\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Checkpoint>
 */
final class DoctrineCheckpointRepository extends ServiceEntityRepository implements CheckpointRepositoryInterface
{
    use DoctrineRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Checkpoint::class);
    }
}