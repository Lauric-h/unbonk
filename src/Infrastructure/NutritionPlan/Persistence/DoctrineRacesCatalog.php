<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Exception\RaceNotFoundException;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImportedRace>
 */
class DoctrineRacesCatalog extends ServiceEntityRepository implements RacesCatalog
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportedRace::class);
    }

    public function add(ImportedRace $race): void
    {
        $this->getEntityManager()->persist($race);
    }

    public function remove(ImportedRace $race): void
    {
        $this->getEntityManager()->remove($race);
    }

    public function get(string $id): ImportedRace
    {
        $race = $this->find($id);

        if (null === $race) {
            throw new RaceNotFoundException($id);
        }

        return $race;
    }

    /**
     * @return ImportedRace[]
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
