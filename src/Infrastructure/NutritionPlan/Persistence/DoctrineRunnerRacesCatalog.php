<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Domain\NutritionPlan\Exception\RaceNotFoundException;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RunnerRace>
 */
class DoctrineRunnerRacesCatalog extends ServiceEntityRepository implements RunnerRacesCatalog
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RunnerRace::class);
    }

    public function add(RunnerRace $race): void
    {
        $this->getEntityManager()->persist($race);
    }

    public function remove(RunnerRace $race): void
    {
        $this->getEntityManager()->remove($race);
    }

    public function get(string $id): RunnerRace
    {
        $race = $this->find($id);

        if (null === $race) {
            throw new RaceNotFoundException($id);
        }

        return $race;
    }

    /**
     * @return RunnerRace[]
     */
    public function findByRunnerId(string $runnerId): array
    {
        return $this->createQueryBuilder('race')
            ->where('race.runnerId = :runnerId')
            ->setParameter('runnerId', $runnerId)
            ->getQuery()
            ->getResult();
    }
}
