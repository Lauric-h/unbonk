<?php

namespace App\Application\NutritionPlan\UseCase\GetEvent;

use App\Application\NutritionPlan\ReadModel\External\ExternalEventReadModel;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetEventQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExternalRacePort $client)
    {
    }

    public function __invoke(GetEventQuery $query): ExternalEventReadModel
    {
        $event = $this->client->getEvent($query->eventId);

        return ExternalEventReadModel::fromDTO($event);
    }
}
