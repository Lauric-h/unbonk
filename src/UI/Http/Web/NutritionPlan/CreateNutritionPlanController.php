<?php

namespace App\UI\Http\Web\NutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAlreadyExistsException;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\Race\Exception\RunnerRaceAccessDeniedException;
use App\Application\Race\Exception\RunnerRaceNotFoundException;
use App\Application\Shared\IdGeneratorInterface;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/races/{runnerRaceId}/nutrition-plan', name: 'app.nutrition_plan.create')]
final class CreateNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(
        string $runnerRaceId,
        #[CurrentUser] UserAdapter $userAdapter
    ): RedirectResponse
    {
        $nutritionPlanId = $this->idGenerator->generate();
        try {
            $this->commandBus->dispatch(new CreateNutritionPlanCommand(
                runnerId: $userAdapter->getUser()->id,
                runnerRaceId: $runnerRaceId,
                nutritionPlanId: $nutritionPlanId,
            ));
        } catch (RunnerRaceNotFoundException) {
            throw $this->createNotFoundException();
        } catch (RunnerRaceAccessDeniedException) {
            throw $this->createNotFoundException();
        } catch (NutritionPlanAlreadyExistsException) {
            $this->addFlash('error', 'Un plan nutrition existe déjà pour cette course.');

            return $this->redirectToRoute('app.my_races.show', ['runnerRaceId' => $runnerRaceId]);
        }

        return $this->redirectToRoute('');
    }
}