<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Checkpoint;

use App\Application\NutritionPlan\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\NutritionPlan\Form\Checkpoint\CheckpointModel;
use App\UI\Http\Web\NutritionPlan\Form\Checkpoint\CheckpointType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/checkpoints/add', name: 'app.checkpoint.add', methods: ['GET', 'POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class AddCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapEntity(id: 'nutritionPlanId')] NutritionPlan $nutritionPlan,
    ): Response {
        $model = new CheckpointModel();
        $form = $this->createForm(CheckpointType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new AddCheckpointCommand(
                nutritionPlanId: $nutritionPlan->id,
                name: $model->name,
                location: $model->location,
                distanceFromStart: $model->distanceFromStart,
                ascentFromStart: $model->ascentFromStart,
                descentFromStart: $model->descentFromStart,
                cutoffTime: $model->cutoffTime,
                assistanceAllowed: $model->assistanceAllowed,
            ));

            $this->addFlash('success', 'Checkpoint ajouté avec succès !');

            return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
        }

        return $this->render('nutrition_plan/checkpoint/add_checkpoint.html.twig', [
            'form' => $form,
            'nutritionPlan' => $nutritionPlan,
        ]);
    }
}
