<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\Shared\IdGeneratorInterface;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\NutritionPlan\Form\NutritionPlan\CreateNutritionPlanModel;
use App\UI\Http\Web\NutritionPlan\Form\NutritionPlan\CreateNutritionPlanType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/nutrition-plans/create', name: 'app.nutrition_plan.create', methods: ['GET', 'POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class CreateNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly IdGeneratorInterface $idGenerator,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapEntity(id: 'raceId')]
        RunnerRace $race,
    ): Response {
        $model = new CreateNutritionPlanModel();
        $form = $this->createForm(CreateNutritionPlanType::class, $model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionPlanId = $this->idGenerator->generate();

            $this->commandBus->dispatch(new CreateNutritionPlanCommand(
                nutritionPlanId: $nutritionPlanId,
                RunnerRaceId: $race->id,
                runnerId: $this->currentUserIdProvider->getCurrentUserId(),
                name: $model->name,
            ));

            $this->addFlash('success', 'Plan de nutrition créé avec succès !');

            return $this->redirectToRoute('app.race.nutrition_plans', ['raceId' => $race->id]);
        }

        return $this->render('nutrition_plan/nutrition_plan/create_nutrition_plan.html.twig', [
            'form' => $form,
            'race' => $race,
        ]);
    }
}
