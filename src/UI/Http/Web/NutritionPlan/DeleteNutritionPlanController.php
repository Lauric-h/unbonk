<?php

namespace App\UI\Http\Web\NutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/nutrition-plans/{id}', name: 'app.nutrition_plan.delete', methods: ['POST'])]
final class DeleteNutritionPlanController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(
        #[CurrentUser] UserAdapter $userAdapter,
        string $id,
    ): RedirectResponse
    {
        try {
            $this->commandBus->dispatch(new DeleteNutritionPlanCommand($userAdapter->getUser()->id, $id));
        } catch (NutritionPlanAccessDeniedException $e) {
            $this->addFlash('error', 'Error deleting nutrition plan: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app.nutrition_plan.list');
    }
}