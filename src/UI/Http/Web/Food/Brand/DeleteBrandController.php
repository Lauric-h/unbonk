<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\DeleteBrand\DeleteBrandCommand;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.delete', methods: ['DELETE'])]
final class DeleteBrandController extends AbstractController
{
    public function __construct(private readonly CommandBus $messageBus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Brand $brand
    ): Response {
        $this->messageBus->dispatch(new DeleteBrandCommand($brand->id));

        return $this->redirectToRoute('app.brand.list');
    }
}
