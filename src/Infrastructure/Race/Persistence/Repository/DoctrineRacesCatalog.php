<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\Race\Entity\Race;
use App\Domain\Race\Exception\RaceNotFoundException;
use App\Domain\Race\Repository\RacesCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

readonly class DoctrineRacesCatalog implements RacesCatalog
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

    public function remove(Race $race): void
    {
        $this->entityManager->remove($race);
    }

    public function getAll(string $runnerId): array
    {
        return $this->entityManager
            ->createQuery('SELECT r FROM App\Domain\Race\Entity\Race r WHERE r.runnerId = :runner')
            ->setParameter('runner', $runnerId)
            ->getResult();
    }

    public function getByIdAndRunnerId(string $id, string $runnerId): Race
    {
        $race = $this->entityManager
            ->createQuery('SELECT r FROM App\Domain\Race\Entity\Race r WHERE r.id = :id AND r.runnerId = :runner')
            ->setParameter('id', $id)
            ->setParameter('runner', $runnerId)
            ->getOneOrNullResult();

        if (null === $race) {
            throw new RaceNotFoundException();
        }

        return $race;
    }
}
