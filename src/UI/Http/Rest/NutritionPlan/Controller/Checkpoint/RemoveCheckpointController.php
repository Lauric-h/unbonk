<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller\Checkpoint;

use App\Application\NutritionPlan\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/race/{raceId}/checkpoints/{checkpointId}', name: 'api.nutrition_plan.remove_checkpoint', methods: ['DELETE'])]
#[IsGranted('EDIT', subject: 'race')]
final class RemoveCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
        #[MapEntity(id: 'checkpointId')] Checkpoint $checkpoint,
    ): JsonResponse {
        $this->commandBus->dispatch(new RemoveCheckpointCommand($race->id, $checkpoint->id));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
