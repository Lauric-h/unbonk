<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteUserRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/races/{raceId}', name: 'api.race.delete', methods: ['DELETE'])]
final class DeleteUserRaceController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(
        #[CurrentUser]
        UserAdapter $userAdapter,
        string $raceId
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteUserRaceCommand($userAdapter->getUser()->id, $raceId));

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
