<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\Exception\RaceCatalogUnavailableException;
use App\Application\Race\UseCase\ListCatalogEvents\ListCatalogEventsQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/catalog/events', name: 'app.catalog.event.list', methods: ['GET'])]
final class ListCatalogEventsController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): Response
    {
        try {
            $events = $this->queryBus->query(new ListCatalogEventsQuery());
        } catch (RaceCatalogUnavailableException) {
            $this->addFlash('error', 'Le catalogue de courses est momentanément indisponible.');
            $events = [];
        }

        return $this->render('race/events.html.twig', ['events' => $events]);
    }
}
