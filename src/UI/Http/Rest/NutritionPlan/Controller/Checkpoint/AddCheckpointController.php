<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller\Checkpoint;

use App\Application\NutritionPlan\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\NutritionPlan\Request\AddCheckpointRequest;
use PhpCsFixer\Runner\Runner;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/race/{raceId}/checkpoints', name: 'api.nutrition_plan.add_checkpoint', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class AddCheckpointController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
        Request $request,
    ): JsonResponse {
        $addCheckpointRequest = $this->serializer->deserialize($request->getContent(), AddCheckpointRequest::class, 'json');

        $cutoffTime = null !== $addCheckpointRequest->cutoffTime
            ? new \DateTimeImmutable($addCheckpointRequest->cutoffTime)
            : null;

        $this->commandBus->dispatch(new AddCheckpointCommand(
            runnerRaceId: $race->id,
            name: $addCheckpointRequest->name,
            location: $addCheckpointRequest->location,
            distanceFromStart: $addCheckpointRequest->distanceFromStart,
            ascentFromStart: $addCheckpointRequest->ascentFromStart,
            descentFromStart: $addCheckpointRequest->descentFromStart,
            cutoffTime: $cutoffTime,
            assistanceAllowed: $addCheckpointRequest->assistanceAllowed,
        ));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('app.race.nutrition_plans', ['raceId' => $race->id])]
        );
    }
}
