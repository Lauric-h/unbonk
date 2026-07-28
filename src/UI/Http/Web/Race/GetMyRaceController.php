<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\Exception\RunnerRaceAccessDeniedException;
use App\Application\Race\Exception\RunnerRaceNotFoundException;
use App\Application\Race\UseCase\GetMyRace\GetMyRaceQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/my-races/{id}', name: 'app.my_races.show', methods: ['GET'])]
final class GetMyRaceController extends AbstractController
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
            $race = $this->queryBus->query(new GetMyRaceQuery(
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

        return $this->render('race/my_race_show.html.twig', [
            'race' => $race,
        ]);
    }
}