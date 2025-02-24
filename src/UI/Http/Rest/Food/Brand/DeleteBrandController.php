<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\DeleteBrand\DeleteBrandCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.delete', methods: ['DELETE'])]
final class DeleteBrandController extends AbstractController
{
    public function __construct(private readonly CommandBus $messageBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->messageBus->dispatch(new DeleteBrandCommand($id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
