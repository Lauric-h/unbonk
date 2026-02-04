<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\ImportRace\ImportRaceCommand;
use App\Application\Shared\IdGeneratorInterface;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/events/{eventId}/races/{raceId}', name: 'api.event.import_race', methods: ['POST'])]
final class ImportRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly IdGeneratorInterface $idGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(
        #[CurrentUser]
        UserAdapter $userAdapter,
        string $eventId,
        string $raceId,
    ): JsonResponse {

        $nutritionPlanId = $this->idGenerator->generate();
        $this->commandBus->dispatch(new ImportRaceCommand(
            nutritionPlanId: $nutritionPlanId,
            externalEventId: $eventId,
            externalRaceId: $raceId,
            runnerId: $userAdapter->getUser()->id,
        ));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('api.nutrition_plan.get', ['nutritionPlanId' => $nutritionPlanId])],
        );
    }
}
