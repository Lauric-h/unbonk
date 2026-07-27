<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\ListRunnerRaces\ListRunnerRacesQuery;
use App\Domain\User\Entity\User;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/races', name: 'app.runner_races.list', methods: ['GET'])]
final class ListRunnerRacesController extends AbstractController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[CurrentUser]
        UserAdapter $user,
    ): Response {
        return $this->render('race/runner_races.html.twig', [
            'race' => $this->queryBus->query(new ListRunnerRacesQuery($user->getUser()->id)),
        ]);
    }
}
