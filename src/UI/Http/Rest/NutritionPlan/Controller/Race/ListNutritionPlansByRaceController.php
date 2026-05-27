<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\Race;

use App\Application\NutritionPlan\UseCase\ListNutritionPlansByRace\ListNutritionPlansByRaceQuery;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/nutrition-plans', name: 'app.race.nutrition_plans', methods: ['GET'])]
#[IsGranted('EDIT', subject: 'race')]
class ListNutritionPlansByRaceController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
    ): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new ListNutritionPlansByRaceQuery($race->id)),
            Response::HTTP_OK,
        );
    }
}