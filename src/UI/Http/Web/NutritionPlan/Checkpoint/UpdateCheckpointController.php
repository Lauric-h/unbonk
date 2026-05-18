<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Checkpoint;

use App\Application\NutritionPlan\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\NutritionPlan\Form\Checkpoint\CheckpointModel;
use App\UI\Http\Web\NutritionPlan\Form\Checkpoint\CheckpointType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/race/{raceId}/checkpoints/{checkpointId}/edit', name: 'app.checkpoint.edit', methods: ['GET', 'POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
        #[MapEntity(id: 'checkpointId')] Checkpoint $checkpoint,
        Request $request
    ): Response {
        if (!$checkpoint->isEditable()) {
            $this->addFlash('error', 'Seuls les checkpoints personnalisés peuvent être modifiés');
            return $this->redirectToRoute('app.race.nutrition_plans', ['raceId' => $race->id]);
        }

        $model = new CheckpointModel(
            name: $checkpoint->name,
            location: $checkpoint->location,
            distanceFromStart: $checkpoint->distanceFromStart,
            ascentFromStart: $checkpoint->ascentFromStart,
            descentFromStart: $checkpoint->descentFromStart,
            cutoffTime: $checkpoint->getCutoff()?->dateTime,
            assistanceAllowed: $checkpoint->isAssistanceAllowed(),
        );

        $form = $this->createForm(CheckpointType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new UpdateCheckpointCommand(
                runnerRaceId: $race->id,
                checkpointId: $checkpoint->id,
                name: $model->name,
                location: $model->location,
                distanceFromStart: $model->distanceFromStart,
                ascentFromStart: $model->ascentFromStart,
                descentFromStart: $model->descentFromStart,
                cutoffTime: $model->cutoffTime,
                assistanceAllowed: $model->assistanceAllowed,
            ));

            $this->addFlash('success', 'Checkpoint modifié avec succès !');

            return $this->redirectToRoute('app.race.nutrition_plans', ['raceId' => $race->id]);
        }

        return $this->render('nutrition_plan/checkpoint/update_checkpoint.html.twig', [
            'form' => $form,
            'race' => $race,
            'checkpoint' => $checkpoint,
        ]);
    }
}
