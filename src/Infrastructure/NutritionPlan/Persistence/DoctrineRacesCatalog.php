<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Exception\RaceNotFoundException;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineRacesCatalog implements RacesCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(ImportedRace $race): void
    {
        $this->entityManager->persist($race);
    }

    public function remove(ImportedRace $race): void
    {
        $this->entityManager->remove($race);
    }

    public function get(string $id): ImportedRace
    {
        $race = $this->entityManager->find(ImportedRace::class, $id);

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
        return $this->entityManager->createQueryBuilder()
            ->select('race')
            ->from(ImportedRace::class, 'race')
            ->where('race.runnerId = :runnerId')
            ->setParameter('runnerId', $runnerId)
            ->getQuery()
            ->getResult();
    }
}
