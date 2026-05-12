<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\Race;

use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteUserRaceCommand;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}', name: 'api.race.delete', methods: ['DELETE'])]
#[IsGranted('EDIT', subject: 'race')]
final class DeleteUserRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    #[IsGranted('DELETE', subject: 'race')]
    public function __invoke(
        #[MapEntity(id: 'raceId')]
        ImportedRace $race,
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteUserRaceCommand($this->currentUserIdProvider->getCurrentUserId(), $race->id));

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
