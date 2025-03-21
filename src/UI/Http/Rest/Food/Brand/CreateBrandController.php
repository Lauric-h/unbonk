<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\UseCase\CreateBrand\CreateBrandCommand;
use App\Application\Food\UseCase\GetBrand\GetBrandQuery;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\SharedKernel\IdGenerator;
use App\UI\Http\Rest\Food\Request\CreateBrandRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/brands', name: 'app.brand.create', methods: ['POST'])]
final class CreateBrandController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $messageBus,
        private readonly QueryBus $queryBus,
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

        $this->messageBus->dispatch($createBrandCommand);

        return new JsonResponse(
            $this->queryBus->query(new GetBrandQuery($id)),
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('app.brand.get', ['id' => $id])]
        );
    }
}
