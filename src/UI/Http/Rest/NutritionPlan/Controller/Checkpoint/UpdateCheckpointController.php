<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller\Checkpoint;

use App\Application\NutritionPlan\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\NutritionPlan\Request\UpdateCheckpointRequest;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/race/{raceId}/checkpoints/{checkpointId}', name: 'api.nutrition_plan.update_checkpoint', methods: ['PUT'])]
#[IsGranted('EDIT', subject: 'race')]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
        #[MapEntity(id: 'checkpointId')] Checkpoint $checkpoint,
        Request $request
    ): JsonResponse {
        $updateCheckpointRequest = $this->serializer->deserialize($request->getContent(), UpdateCheckpointRequest::class, 'json');

        $cutoffTime = null !== $updateCheckpointRequest->cutoffTime
            ? new \DateTimeImmutable($updateCheckpointRequest->cutoffTime)
            : null;

        $this->commandBus->dispatch(new UpdateCheckpointCommand(
            runnerRaceId: $race->id,
            checkpointId: $checkpoint->id,
            name: $updateCheckpointRequest->name,
            location: $updateCheckpointRequest->location,
            distanceFromStart: $updateCheckpointRequest->distanceFromStart,
            ascentFromStart: $updateCheckpointRequest->ascentFromStart,
            descentFromStart: $updateCheckpointRequest->descentFromStart,
            cutoffTime: $cutoffTime,
            assistanceAllowed: $updateCheckpointRequest->assistanceAllowed,
        ));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
