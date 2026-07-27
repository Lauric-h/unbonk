<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\Exception\RaceCatalogUnavailableException;
use App\Application\Race\UseCase\GetCatalogEvent\GetCatalogEventQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/catalog/event/{eventId}/races', name: 'app.catalog.event.races', methods: ['GET'])]
final class GetCatalogEventController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $eventId): Response
    {
        $event = null;
        try {
            $event = $this->queryBus->query(new GetCatalogEventQuery($eventId));
        } catch (RaceCatalogUnavailableException) {
            $this->addFlash('error', 'Le catalogue de courses est momentanément indisponible.');
        }

        if ($event === null) {
            return $this->redirectToRoute('app.catalog.event.list');
        }

        return $this->render('race/catalog/event.html.twig', [
            'event' => $event,
        ]);
    }
}