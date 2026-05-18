<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Checkpoint;

use App\Application\NutritionPlan\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/race/{raceId}/checkpoints/{checkpointId}/delete', name: 'app.checkpoint.delete', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class RemoveCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
        string $checkpointId
    ): Response {
        $this->commandBus->dispatch(new RemoveCheckpointCommand(
            runnerRaceId: $race->id,
            checkpointId: $checkpointId,
        ));

        $this->addFlash('success', 'Checkpoint supprimé avec succès !');

        return $this->redirectToRoute('app.race.nutrition_plans', ['raceId' => $race->id]);
    }
}
