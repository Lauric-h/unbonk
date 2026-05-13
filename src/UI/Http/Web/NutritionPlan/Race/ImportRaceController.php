<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Race;

use App\Application\NutritionPlan\UseCase\ImportRace\ImportRaceCommand;
use App\Application\Shared\IdGeneratorInterface;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events/{eventId}/races/{raceId}/import', name: 'app.race.import', methods: ['POST'])]
final class ImportRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly IdGeneratorInterface $idGenerator,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(string $eventId, string $raceId): Response
    {
        $this->commandBus->dispatch(new ImportRaceCommand(
            nutritionPlanId: $this->idGenerator->generate(),
            externalEventId: $eventId,
            externalRaceId: $raceId,
            runnerId: $this->currentUserIdProvider->getCurrentUserId(),
        ));

        $this->addFlash('success', 'Course importée avec succès !');

        return $this->redirectToRoute('app.race.list');
    }
}
