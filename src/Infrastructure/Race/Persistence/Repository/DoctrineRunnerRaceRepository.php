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

    public function existsForRunner(string $runnerId, string $sourceRaceId): bool
    {
        $result = $this->getEntityManager()->createQueryBuilder()
            ->select('1')
            ->from(RunnerRace::class, 'rr')
            ->where('rr.runnerId = :runnerId')
            ->andWhere('rr.sourceRaceId = :sourceRaceId')
            ->setParameter('runnerId', $runnerId)
            ->setParameter('sourceRaceId', $sourceRaceId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return null !== $result;
    }

    public function findByRunnerId(string $runnerId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.runnerId = :runnerId')
            ->setParameter('runnerId', $runnerId)
            ->getQuery()
            ->getResult();
    }
}