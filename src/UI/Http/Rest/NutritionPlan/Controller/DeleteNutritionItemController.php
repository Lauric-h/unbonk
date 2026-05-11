<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/nutrition-items/{nutritionItemId}', name: 'api.nutrition_plan.segment.delete_nutrition_item', methods: ['DELETE'])]
final class DeleteNutritionItemController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    #[IsGranted('DELETE', subject: 'nutritionPlan')]
    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
        string $segmentId,
        string $nutritionItemId,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteNutritionItemCommand($nutritionPlan->id, $segmentId, $nutritionItemId));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
