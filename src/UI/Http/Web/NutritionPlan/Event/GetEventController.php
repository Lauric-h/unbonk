<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Event;

use App\Domain\NutritionPlan\Port\ExternalRacePort;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events/{eventId}', name: 'app.event.get', methods: ['GET'])]
final class GetEventController extends AbstractController
{
    public function __construct(
        private readonly ExternalRacePort $externalRacePort,
    ) {
    }

    public function __invoke(string $eventId): Response
    {
        $event = $this->externalRacePort->getEvent($eventId);

        return $this->render('nutrition_plan/event/get_event.html.twig', [
            'event' => $event,
        ]);
    }
}
