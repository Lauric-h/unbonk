<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Checkpoint;

use App\Application\NutritionPlan\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
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

#[Route('/nutrition-plans/{nutritionPlanId}/checkpoints/{checkpointId}/edit', name: 'app.checkpoint.edit', methods: ['GET', 'POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapEntity(id: 'nutritionPlanId')] NutritionPlan $nutritionPlan,
        string $checkpointId,
    ): Response {
        $checkpoint = $nutritionPlan->getCheckpointById($checkpointId);

        if (!$checkpoint) {
            throw $this->createNotFoundException('Checkpoint non trouvé');
        }

        if (!$checkpoint->isEditable()) {
            $this->addFlash('error', 'Seuls les checkpoints personnalisés peuvent être modifiés');
            return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
        }

        $model = new CheckpointModel(
            name: $checkpoint->getName(),
            location: $checkpoint->getLocation(),
            distanceFromStart: $checkpoint->getDistanceFromStart(),
            ascentFromStart: $checkpoint->getAscentFromStart(),
            descentFromStart: $checkpoint->getDescentFromStart(),
            cutoffTime: $checkpoint->getCutoff()?->dateTime,
            assistanceAllowed: $checkpoint->isAssistanceAllowed(),
        );

        $form = $this->createForm(CheckpointType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new UpdateCheckpointCommand(
                nutritionPlanId: $nutritionPlan->id,
                checkpointId: $checkpointId,
                name: $model->name,
                location: $model->location,
                distanceFromStart: $model->distanceFromStart,
                ascentFromStart: $model->ascentFromStart,
                descentFromStart: $model->descentFromStart,
                cutoffTime: $model->cutoffTime,
                assistanceAllowed: $model->assistanceAllowed,
            ));

            $this->addFlash('success', 'Checkpoint modifié avec succès !');

            return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
        }

        return $this->render('nutrition_plan/checkpoint/update_checkpoint.html.twig', [
            'form' => $form,
            'nutritionPlan' => $nutritionPlan,
            'checkpoint' => $checkpoint,
        ]);
    }
}
