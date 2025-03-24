<?php

namespace App\Infrastructure\Race\Persistence;

use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Exception\CheckpointNotFoundException;
use App\Domain\Race\Repository\CheckpointsCatalog;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineCheckpointsCatalog implements CheckpointsCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getByIdAndRaceId(string $id, string $raceId): Checkpoint
    {
        $checkpoint = $this->entityManager->createQueryBuilder()
            ->select('cp')
            ->from(Checkpoint::class, 'cp')
            ->where('cp.id = :id')
            ->andWhere('cp.race = :raceId')
            ->setParameter('id', $id)
            ->setParameter('raceId', $raceId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $checkpoint) {
            throw new CheckpointNotFoundException($id);
        }

        return $checkpoint;
    }
}
