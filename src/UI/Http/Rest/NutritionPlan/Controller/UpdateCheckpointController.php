<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Domain\NutritionPlan\Service\NutritionPlanAccessService;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use App\UI\Http\Rest\NutritionPlan\Request\UpdateCheckpointRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/nutrition-plans/{nutritionPlanId}/checkpoints/{checkpointId}', name: 'api.nutrition_plan.update_checkpoint', methods: ['PUT'])]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
        private readonly NutritionPlanAccessService $nutritionPlanAccessService,
    ) {
    }

    public function __invoke(
        string $nutritionPlanId,
        string $checkpointId,
        Request $request,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        $this->nutritionPlanAccessService->checkAccess($nutritionPlanId, $userAdapter->getUser()->id);

        $updateCheckpointRequest = $this->serializer->deserialize($request->getContent(), UpdateCheckpointRequest::class, 'json');

        $cutoffTime = null !== $updateCheckpointRequest->cutoffTime
            ? new \DateTimeImmutable($updateCheckpointRequest->cutoffTime)
            : null;

        $this->commandBus->dispatch(new UpdateCheckpointCommand(
            nutritionPlanId: $nutritionPlanId,
            checkpointId: $checkpointId,
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
