<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/checkpoints/{checkpointId}', name: 'api.nutrition_plan.remove_checkpoint', methods: ['DELETE'])]
final class RemoveCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    #[IsGranted('EDIT', subject: 'nutritionPlan')]
    public function __invoke(
        NutritionPlan $nutritionPlan,
        string $checkpointId,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        $this->commandBus->dispatch(new RemoveCheckpointCommand($nutritionPlan->id, $checkpointId));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
