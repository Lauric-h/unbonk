<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\User\Security\UserAdapter;
use App\UI\Http\Rest\NutritionPlan\Request\AddCheckpointRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/nutrition-plans/{nutritionPlanId}/checkpoints', name: 'api.nutrition_plan.add_checkpoint', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class AddCheckpointController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(
        NutritionPlan $nutritionPlan,
        Request $request,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        $addCheckpointRequest = $this->serializer->deserialize($request->getContent(), AddCheckpointRequest::class, 'json');

        $cutoffTime = null !== $addCheckpointRequest->cutoffTime
            ? new \DateTimeImmutable($addCheckpointRequest->cutoffTime)
            : null;

        $this->commandBus->dispatch(new AddCheckpointCommand(
            nutritionPlanId: $nutritionPlan->id,
            name: $addCheckpointRequest->name,
            location: $addCheckpointRequest->location,
            distanceFromStart: $addCheckpointRequest->distanceFromStart,
            ascentFromStart: $addCheckpointRequest->ascentFromStart,
            descentFromStart: $addCheckpointRequest->descentFromStart,
            cutoffTime: $cutoffTime,
            assistanceAllowed: $addCheckpointRequest->assistanceAllowed,
        ));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('api.nutrition_plan.get', ['nutritionPlanId' => $nutritionPlan->id])]
        );
    }
}
