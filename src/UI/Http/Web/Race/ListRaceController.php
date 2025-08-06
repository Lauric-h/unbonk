<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\ListRace\ListRaceQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races', name: 'app.race.list', methods: ['GET'])]
final class ListRaceController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): Response
    {
        return $this->render('Race/list_race.html.twig', [
            /* @phpstan-ignore-next-line */
            'list' => $this->queryBus->query(new ListRaceQuery($this->getUser()->getUser()->id)),
        ]);
    }
}
