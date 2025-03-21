<?php

namespace App\Infrastructure\Race\Persistence;

use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Exception\CheckpointNotFoundException;
use App\Domain\Race\Repository\CheckpointsCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

final class DoctrineCheckpointsCatalog implements CheckpointsCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(Checkpoint $checkpoint): void
    {
        try {
            $this->entityManager->persist($checkpoint);
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to add checkpoint with id %s', $checkpoint->id));
        }
    }

    public function remove(string $id, string $raceId, string $runnerId): void
    {
        $checkpoint = $this->get($id, $raceId, $runnerId);

        $this->entityManager->remove($checkpoint);
    }

    public function get(string $id, string $raceId, string $runnerId): Checkpoint
    {
        $checkpoint = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Checkpoint::class, 'c')
            ->join('c.race', 'r')
            ->where('c.id = :id')
            ->andWhere('r.id = :raceId')
            ->andWhere('r.runnerId = :runnerId')
            ->setParameter('id', $id)
            ->setParameter('raceId', $raceId)
            ->setParameter('runnerId', $runnerId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $checkpoint) {
            throw new CheckpointNotFoundException($id);
        }

        return $checkpoint;
    }
}
