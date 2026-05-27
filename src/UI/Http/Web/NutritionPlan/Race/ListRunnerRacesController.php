<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Race;

use App\Application\NutritionPlan\UseCase\ListRunnerRaces\ListRunnerRacesQuery;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races', name: 'app.race.list', methods: ['GET'])]
final class ListRunnerRacesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(): Response
    {
        $races = $this->queryBus->query(new ListRunnerRacesQuery(
            userId: $this->currentUserIdProvider->getCurrentUserId(),
        ));

        return $this->render('nutrition_plan/race/list_races.html.twig', [
            'races' => $races,
        ]);
    }
}
