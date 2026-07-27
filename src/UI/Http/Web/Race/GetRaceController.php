<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\Exception\RaceCatalogUnavailableException;
use App\Application\Race\UseCase\GetCatalogRace\GetCatalogRaceQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/catalog/event/{eventId}/races/{raceId}', name: 'app.cataloge.event.race', methods: ['GET'])]
final class GetRaceController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $eventId, string $raceId): Response
    {
        $race = null;
        try {
            $race = $this->queryBus->query(new GetCatalogRaceQuery($eventId, $raceId));
        } catch (RaceCatalogUnavailableException) {
            $this->addFlash('error', 'Le catalogue de courses est momentanément indisponible.');
        }

        if ($race === null) {
            return $this->redirectToRoute('app.catalog.event.races', ['eventId' => $eventId]);
        }

        return $this->render('race/catalog/race.html.twig', [
            'race' => $race,
        ]);
    }
}