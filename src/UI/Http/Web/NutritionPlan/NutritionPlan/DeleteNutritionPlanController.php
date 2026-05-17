<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/delete', name: 'app.nutrition_plan.delete', methods: ['POST'])]
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
    ): Response {
        $raceId = $nutritionPlan->runnerRace->id;

        $this->commandBus->dispatch(new DeleteNutritionPlanCommand(
            nutritionPlanId: $nutritionPlan->id,
        ));

        $this->addFlash('success', 'Plan de nutrition supprimé avec succès !');

        return $this->redirectToRoute('app.race.nutrition_plans', ['raceId' => $raceId]);
    }
}
