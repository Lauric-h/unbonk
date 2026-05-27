<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller\NutritionPlan;

use App\Application\NutritionPlan\UseCase\UpdateNutritionPlan\UpdateNutritionPlanCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\NutritionPlan\Request\UpdateNutritionPlanRequest;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/race/{raceId}/checkpoints/{checkpointId}', name: 'api.nutrition_plan.update', methods: ['PATCH'])]
#[IsGranted('EDIT', subject: 'race')]
final class UpdateNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
        #[MapRequestPayload]
        UpdateNutritionPlanRequest $request,
    ): JsonResponse {
        $this->commandBus->dispatch(new UpdateNutritionPlanCommand(
            nutritionPlanId: $nutritionPlan->id,
            name: $request->name, // @phpstan-ignore-line assert in Request
        ));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
