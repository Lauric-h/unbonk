<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\Race;

use App\Application\NutritionPlan\UseCase\GetRunnerRace\GetRunnerRaceQuery;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}', name: 'api.race.get', methods: ['GET'])]
#[IsGranted('EDIT', subject: 'race')]
final class GetRunnerRaceController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
    ): JsonResponse {
        return new JsonResponse(
            $this->queryBus->query(new GetRunnerRaceQuery($race->id)),
            Response::HTTP_OK,
        );
    }
}
