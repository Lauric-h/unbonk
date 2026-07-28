<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\Exception\RunnerRaceAccessDeniedException;
use App\Application\Race\Exception\RunnerRaceNotFoundException;
use App\Application\Race\UseCase\GetRunnerRace\GetRunnerRaceQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/races/{id}', name: 'app.runner_races.show', methods: ['GET'])]
final class GetRunnerRaceController extends AbstractController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[CurrentUser] UserAdapter $user,
        string $id,
    ): Response
    {
        try {
            $race = $this->queryBus->query(new GetRunnerRaceQuery(
                runnerId: $user->getUser()->id,
                raceId: $id,
            ));
        } catch (RunnerRaceNotFoundException) {
            throw $this->createNotFoundException('Cette course n\'existe pas.');
        } catch (RunnerRaceAccessDeniedException) {
            // Volontairement 404 plutôt que 403 : ne pas révéler qu'une course
            // avec cet ID existe mais appartient à quelqu'un d'autre.
            throw $this->createNotFoundException('Cette course n\'existe pas.');
        }

        return $this->render('race/runner_race.html.twig', [
            'race' => $race,
        ]);
    }
}