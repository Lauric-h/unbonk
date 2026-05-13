<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Event;

use App\Domain\NutritionPlan\Port\ExternalRacePort;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events', name: 'app.event.list', methods: ['GET'])]
final class ListEventsController extends AbstractController
{
    public function __construct(
        private readonly ExternalRacePort $externalRacePort,
    ) {
    }

    public function __invoke(): Response
    {
        $events = $this->externalRacePort->listAllEvents();

        return $this->render('nutrition_plan/event/list_events.html.twig', [
            'events' => $events,
        ]);
    }
}
