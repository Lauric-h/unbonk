<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Entity\Segment;
use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;
use App\Domain\Race\Repository\SegmentRepositoryInterface;
use App\Infrastructure\Shared\Persistence\Repository\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Segment>
 */
final class DoctrineSegmentRepository extends ServiceEntityRepository implements SegmentRepositoryInterface
{
    use DoctrineRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Segment::class);
    }
}