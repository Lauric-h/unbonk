<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Race;

use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteRunnerRaceCommand;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/delete', name: 'app.race.delete', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class DeleteRunnerRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
    ): Response {
        $this->commandBus->dispatch(new DeleteRunnerRaceCommand(
            raceId: $race->id,
        ));

        $this->addFlash('success', 'Course supprimée avec succès !');

        return $this->redirectToRoute('app.race.list');
    }
}
