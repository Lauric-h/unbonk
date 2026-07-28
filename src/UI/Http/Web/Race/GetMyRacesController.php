<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\GetMyRaces\GetMyRacesQuery;
use App\Domain\User\Entity\User;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/my-races', name: 'app.my_races.list', methods: ['GET'])]
final class GetMyRacesController extends AbstractController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[CurrentUser]
        UserAdapter $user,
    ): Response {
        return $this->render('race/my_races_list.html.twig', [
            'races' => $this->queryBus->query(new GetMyRacesQuery($user->getUser()->id)),
        ]);
    }
}
