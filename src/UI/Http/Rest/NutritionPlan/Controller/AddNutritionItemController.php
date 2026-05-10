<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use App\UI\Http\Rest\NutritionPlan\Request\AddNutritionItemRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/nutrition-items', name: 'api.nutrition_plan.segment.add_nutrition_item', methods: ['POST'])]
final class AddNutritionItemController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[IsGranted('EDIT', subject: 'nutritionPlan')]
    public function __invoke(
        NutritionPlan $nutritionPlan,
        string $segmentId,
        Request $request,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        $addNutritionItemRequest = $this->serializer->deserialize($request->getContent(), AddNutritionItemRequest::class, 'json');

        $this->commandBus->dispatch(new AddNutritionItemCommand($addNutritionItemRequest->id, $nutritionPlan->id, $segmentId, $addNutritionItemRequest->quantity));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('api.nutrition_plan.get', ['nutritionPlanId' => $nutritionPlan->id])]
        );
    }
}
