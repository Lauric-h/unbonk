<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\DeleteBrand\DeleteBrandCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.delete', methods: ['DELETE'])]
final class DeleteBrandController extends AbstractController
{
    public function __construct(private readonly CommandBus $messageBus)
    {
    }

    public function __invoke(string $id): Response
    {
        $this->messageBus->dispatch(new DeleteBrandCommand($id));

        return $this->redirectToRoute('app.brand.list');
    }
}
