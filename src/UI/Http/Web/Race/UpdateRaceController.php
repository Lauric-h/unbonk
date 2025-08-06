<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\GetRace\GetRaceQuery;
use App\Application\Race\UseCase\UpdateRace\UpdateRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\UI\Http\Web\Race\Form\UpdateRace\UpdateRaceForm;
use App\UI\Http\Web\Race\Form\UpdateRace\UpdateRaceModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{id}/edit', name: 'app.race.update')]
final class UpdateRaceController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(string $id, Request $request): Response
    {
        /* @phpstan-ignore-next-line */
        $runnerId = $this->getUser()->getUser()->id;
        $race = $this->queryBus->query(new GetRaceQuery($id, $runnerId));

        $updateRaceModel = new UpdateRaceModel(
            name: $race->name,
            date: new DatePoint($race->date),
            distance: $race->profile->distance,
            ascent: $race->profile->ascent,
            descent: $race->profile->descent,
            city: $race->address->city,
            postalCode: $race->address->postalCode,
        );

        $form = $this->createForm(UpdateRaceForm::class, $updateRaceModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = new UpdateRaceCommand(
                id: $race->id,
                runnerId: $runnerId,
                date: $updateRaceModel->date,
                name: $updateRaceModel->name,
                distance: $updateRaceModel->distance,
                elevationGain: $updateRaceModel->ascent,
                elevationLoss: $updateRaceModel->descent,
                city: $updateRaceModel->city,
                postalCode: $updateRaceModel->postalCode,
            );

            $this->commandBus->dispatch($command);

            return $this->redirectToRoute('app.race.get', ['id' => $race->id]);
        }

        return $this->render('Race/update_race.html.twig', [
            'form' => $form,
            'race' => $race,
        ]);
    }
}
