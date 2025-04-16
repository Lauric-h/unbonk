<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\NutritionPlan\Request\UpdateNutritionItemRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/nutrition-items/{itemId}', name: 'app.nutrition_plan.segment.update_quantity', methods: ['POST'])]
final class UpdateNutritionItemQuantityController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus, private SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request, string $segmentId, string $itemId): JsonResponse
    {
        $updateRequest = $this->serializer->deserialize($request->getContent(), UpdateNutritionItemRequest::class, 'json');
        $this->commandBus->dispatch(new UpdateNutritionItemQuantityCommand($segmentId, $itemId, $updateRequest->quantity));

        return new JsonResponse([], Response::HTTP_OK);
    }
}
