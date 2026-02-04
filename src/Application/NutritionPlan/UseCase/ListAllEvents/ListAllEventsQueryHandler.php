<?php

namespace App\Application\NutritionPlan\UseCase\ListAllEvents;

use App\Application\NutritionPlan\ReadModel\External\ExternalEventReadModel;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListAllEventsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExternalRacePort $raceClient)
    {
    }

    /**
     * @return ExternalEventReadModel[]
     */
    public function __invoke(ListAllEventsQuery $query): array
    {
        $events = $this->raceClient->listAllEvents();

        return array_map(
            static fn (ExternalEventDTO $dto) => ExternalEventReadModel::fromDTO($dto),
            $events,
        );
    }
}
