<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\CreateRace\CreateRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\SharedKernel\IdGenerator;
use App\UI\Http\Web\Race\Form\CreateRace\CreateRaceForm;
use App\UI\Http\Web\Race\Form\CreateRace\CreateRaceModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/new', name: 'app.race.create')]
final class CreateRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly IdGenerator $idGenerator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $createRaceModel = new CreateRaceModel();
        $form = $this->createForm(CreateRaceForm::class, $createRaceModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $this->idGenerator->generate();
            /** @phpstan-ignore-next-line  */
            $runnerId = $this->getUser()->getUser()->id;

            $command = new CreateRaceCommand(
                id: $id,
                runnerId: $runnerId,
                date: $createRaceModel->date, // @phpstan-ignore-line check is made in model
                name: $createRaceModel->name, // @phpstan-ignore-line check is made in model
                distance: $createRaceModel->distance, // @phpstan-ignore-line check is made in model
                ascent: $createRaceModel->ascent, // @phpstan-ignore-line check is made in model
                descent: $createRaceModel->descent, // @phpstan-ignore-line check is made in model
                city: $createRaceModel->city, // @phpstan-ignore-line check is made in model
                postalCode: $createRaceModel->postalCode, // @phpstan-ignore-line check is made in model
            );

            $this->commandBus->dispatch($command);

            return $this->redirectToRoute('app.race.get', ['id' => $id]);
        }

        return $this->render('Race/create_race.html.twig', [
            'form' => $form,
        ]);
    }
}
