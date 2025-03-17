<?php

namespace App\Infrastructure\Race\Persistence;

use App\Domain\Race\Entity\Race;
use App\Domain\Race\Exception\RaceNotFoundException;
use App\Domain\Race\Repository\RacesCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

final readonly class DoctrineRacesCatalog implements RacesCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(Race $race): void
    {
        try {
            $this->entityManager->persist($race);
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to add race with id %s', $race->id));
        }
    }

    public function get(string $id): Race
    {
        $race = $this->entityManager->find(Race::class, $id);

        if (null === $race) {
            throw new RaceNotFoundException();
        }

        return $race;
    }

    public function remove(string $id): void
    {
        $race = $this->entityManager->getReference(Race::class, $id);

        if (null === $race) {
            throw new RaceNotFoundException();
        }

        $this->entityManager->remove($race);
    }

    public function getAll(string $runnerId): array
    {
        return $this->entityManager
            ->createQuery('SELECT r FROM App\Domain\Race\Entity\Race r WHERE r.runner_id = :runner')
            ->setParameter('runner_id', $runnerId)
            ->getResult();
    }
}
