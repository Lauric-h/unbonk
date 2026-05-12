<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\NutritionPlan;

use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use App\UI\Http\Rest\NutritionPlan\Request\UpdateNutritionItemRequest;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/nutrition-items/{itemId}', name: 'api.nutrition_plan.segment.update_quantity', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class UpdateNutritionItemQuantityController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
        string $segmentId,
        string $itemId,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        $updateRequest = $this->serializer->deserialize($request->getContent(), UpdateNutritionItemRequest::class, 'json');
        $this->commandBus->dispatch(new UpdateNutritionItemQuantityCommand($segmentId, $itemId, $updateRequest->quantity));

        return new JsonResponse([], Response::HTTP_OK);
    }
}
