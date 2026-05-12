<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller\NutritionPlan;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}', name: 'api.nutrition_plan.delete', methods: ['DELETE'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class DeleteNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteNutritionPlanCommand(
            nutritionPlanId: $nutritionPlan->id,
        ));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
