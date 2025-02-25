<?php

namespace App\UI\Http\Rest\Food\Food;

use App\Infrastructure\Shared\Bus\CommandBus;
use App\SharedKernel\IdGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/brands/{brandId}/foods', name: 'app.brand.food.create', methods: ['POST'])]
final class CreateFoodController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly IdGenerator $idGenerator,
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function __invoke(string $brandId): JsonResponse
    {
        $id = $this->idGenerator->generate();
        $this->commandBus->dispatch(new CreateFoodCommand(
            id: $id,
            brandId: $brandId,
        ));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('app.brand.food.get', ['brandId' => $brandId, 'id' => $id])]
        );
    }
}