<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/nutrition-items/{nutritionItemId}', name: 'app.nutrition_plan.segment.delete_nutrition_item', methods: ['DELETE'])]
final class DeleteNutritionItemController extends AbstractController
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(string $nutritionPlanId, string $segmentId, string $nutritionItemId): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteNutritionItemCommand($nutritionPlanId, $segmentId, $nutritionItemId));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
