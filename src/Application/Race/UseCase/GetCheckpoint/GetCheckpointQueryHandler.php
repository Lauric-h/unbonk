<?php

namespace App\Application\Race\UseCase\GetCheckpoint;

use App\Application\Race\ReadModel\CheckpointReadModel;
use App\Domain\Race\Repository\CheckpointsCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetCheckpointQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CheckpointsCatalog $checkpointsCatalog)
    {
    }

    public function __invoke(GetCheckpointQuery $query): CheckpointReadModel
    {
        $checkpoint = $this->checkpointsCatalog->getByIdAndRaceId($query->checkpointId, $query->raceId);

        return CheckpointReadModel::fromCheckpoint($checkpoint);
    }
}
