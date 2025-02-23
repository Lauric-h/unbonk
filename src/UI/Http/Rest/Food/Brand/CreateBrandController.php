<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\CreateBrand\CreateBrandCommand;
use App\Application\Food\CreateBrand\CreateBrandRequest;
use App\SharedKernel\IdGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/brands', name: 'app.brand.create', methods: ['POST'])]
final class CreateBrandController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly IdGenerator $idGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $brandRequest = $this->serializer->deserialize($request->getContent(), CreateBrandRequest::class, 'json');

        $id = $this->idGenerator->generate();
        $createBrandCommand = new CreateBrandCommand(
            id: $id,
            name: $brandRequest->name,
        );

        $this->bus->dispatch($createBrandCommand);

        return new JsonResponse(
            [],
            Response::HTTP_NO_CONTENT,
            ['Location' => $this->urlGenerator->generate('app.brand.get', ['id' => $id])]
        );
    }
}
