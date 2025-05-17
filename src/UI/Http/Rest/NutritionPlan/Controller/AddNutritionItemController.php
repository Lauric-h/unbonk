<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommand;
use App\Domain\NutritionPlan\Service\NutritionPlanAccessService;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\NutritionPlan\Request\AddNutritionItemRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/nutrition-items', name: 'app.nutrition_plan.segment.add_nutrition_item', methods: ['POST'])]
final class AddNutritionItemController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly NutritionPlanAccessService $nutritionPlanAccessService,
    ) {
    }

    public function __invoke(string $nutritionPlanId, string $segmentId, Request $request): JsonResponse
    {
        /* @phpstan-ignore-next-line */
        $this->nutritionPlanAccessService->checkAccess($nutritionPlanId, $this->getUser()->getUser()->id);

        $addNutritionItemRequest = $this->serializer->deserialize($request->getContent(), AddNutritionItemRequest::class, 'json');

        $this->commandBus->dispatch(new AddNutritionItemCommand($addNutritionItemRequest->id, $nutritionPlanId, $segmentId, $addNutritionItemRequest->quantity));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('app.nutrition_plan.get', ['nutritionPlanId' => $nutritionPlanId])]
        );
    }
}
