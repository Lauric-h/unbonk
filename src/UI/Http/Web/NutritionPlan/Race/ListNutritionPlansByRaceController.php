<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Race;

use App\Application\NutritionPlan\UseCase\ListNutritionPlansByRace\ListNutritionPlansByRaceQuery;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/nutrition-plans', name: 'app.race.nutrition_plans', methods: ['GET'])]
#[IsGranted('EDIT', subject: 'race')]
final class ListNutritionPlansByRaceController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
    ): Response {
        $nutritionPlans = $this->queryBus->query(new ListNutritionPlansByRaceQuery(
            raceId: $race->id,
        ));

        return $this->render('nutrition_plan/race/list_nutrition_plans_by_race.html.twig', [
            'race' => $race,
            'nutritionPlans' => $nutritionPlans,
        ]);
    }
}
