<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Checkpoint;

use App\Application\NutritionPlan\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/checkpoints/{checkpointId}/delete', name: 'app.checkpoint.delete', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class RemoveCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')] NutritionPlan $nutritionPlan,
        string $checkpointId,
    ): Response {
        $this->commandBus->dispatch(new RemoveCheckpointCommand(
            nutritionPlanId: $nutritionPlan->id,
            checkpointId: $checkpointId,
        ));

        $this->addFlash('success', 'Checkpoint supprimé avec succès !');

        return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
    }
}
