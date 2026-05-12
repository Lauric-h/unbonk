<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller\NutritionPlan;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\Shared\IdGeneratorInterface;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\NutritionPlan\Request\CreateNutritionPlanRequest;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/imported-races/{raceId}/nutrition-plans', name: 'api.nutrition_plan.create', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'race')]
final class CreateNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
        private readonly IdGeneratorInterface $idGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'raceId')]
        ImportedRace $race,
        Request $request,
    ): JsonResponse {
        $createRequest = $this->serializer->deserialize(
            $request->getContent(),
            CreateNutritionPlanRequest::class,
            'json'
        );

        $nutritionPlanId = $this->idGenerator->generate();

        $this->commandBus->dispatch(new CreateNutritionPlanCommand(
            nutritionPlanId: $nutritionPlanId,
            importedRaceId: $race->id,
            runnerId: $this->currentUserIdProvider->getCurrentUserId(),
            name: $createRequest->name,
        ));

        return new JsonResponse(
            ['id' => $nutritionPlanId],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('api.nutrition_plan.get', ['nutritionPlanId' => $nutritionPlanId])]
        );
    }
}
