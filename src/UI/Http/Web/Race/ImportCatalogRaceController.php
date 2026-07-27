<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\Exception\CatalogRaceNotFoundException;
use App\Application\Race\Exception\RaceAlreadyImportedException;
use App\Application\Race\Exception\RaceCatalogUnavailableException;
use App\Application\Race\UseCase\ImportRace\ImportRaceCommand;
use App\Domain\User\Entity\User;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/catalog/event/{eventId}/races/{raceId}', name: 'app.catalog.event.race.import', methods: ['POST'])]
final class ImportCatalogRaceController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(
        #[CurrentUser] UserAdapter $user,
        string $eventId,
        string $raceId,
    ): RedirectResponse
    {
        try {
            $this->commandBus->dispatch(new ImportRaceCommand(
                runnerId: $user->getUser()->id,
                eventId: $eventId,
                raceId: $raceId
            ));

        } catch (RaceAlreadyImportedException) {
            $this->addFlash('error', 'La course a déjà été importée.');

            return $this->redirectToRoute('app.catalog.event.get', ['eventId' => $eventId]);
        } catch (CatalogRaceNotFoundException) {
            throw $this->createNotFoundException('Cette course n\'existe pas ou n\'est plus disponible.');
        } catch (RaceCatalogUnavailableException) {
            $this->addFlash('error', 'Le catalogue de courses est momentanément indisponible.');

            return $this->redirectToRoute('app.catalog.event.get', ['eventId' => $eventId]);
        }

        $this->addFlash('success', 'La course a été importée avec succès.');

        return $this->redirectToRoute('app.runner_races.list');
    }
}