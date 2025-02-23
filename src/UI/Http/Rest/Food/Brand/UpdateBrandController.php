<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\UpdateBrand\UpdateBrandCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/brands/{id}', name: 'app.brand.update', methods: ['PUT'])]
final class UpdateBrandController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), UpdateBrandCommand::class, 'json');

        $this->messageBus->dispatch($command);

        return new JsonResponse(
            [],
            Response::HTTP_NO_CONTENT,
            ['Location' => $this->urlGenerator->generate('app.brand.get', ['id' => $command->id])]
        );
    }
}
