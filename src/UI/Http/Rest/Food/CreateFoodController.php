<?php

namespace App\UI\Http\Rest\Food;

use App\Application\Food\CreateBrand\CreateBrandCommand;
use App\Application\Food\CreateBrand\CreateBrandCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/brands', name: 'app.brand.create', methods: ['POST'])]
final class CreateFoodController extends AbstractController
{
    public function __construct(
        private readonly CreateBrandCommandHandler $handler,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $createBrandCommand = $this->serializer->deserialize(
            $request->getContent(),
            CreateBrandCommand::class,
            'json');

        ($this->handler)($createBrandCommand);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}