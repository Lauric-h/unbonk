<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;
use App\Infrastructure\Shared\Persistence\Repository\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RunnerRace>
 */
final class DoctrineRunnerRaceRepository extends ServiceEntityRepository implements RunnerRaceRepositoryInterface
{
    use DoctrineRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RunnerRace::class);
    }
}