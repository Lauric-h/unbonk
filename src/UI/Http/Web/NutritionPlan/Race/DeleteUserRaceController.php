<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Race;

use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteUserRaceCommand;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/delete', name: 'app.race.delete', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class DeleteUserRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        ImportedRace $race,
    ): Response {
        $this->commandBus->dispatch(new DeleteUserRaceCommand(
            raceId: $race->id,
        ));

        $this->addFlash('success', 'Course supprimée avec succès !');

        return $this->redirectToRoute('app.race.list');
    }
}
