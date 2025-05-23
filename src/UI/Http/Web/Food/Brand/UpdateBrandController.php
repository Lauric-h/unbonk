<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\UpdateBrand\UpdateBrandCommand;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Exception\BrandAlreadyExistsException;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\Food\Form\UpdateBrand\UpdateBrandForm;
use App\UI\Http\Web\Food\Form\UpdateBrand\UpdateBrandModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}/update', name: 'app.brand.update')]
final class UpdateBrandController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $messageBus,
    ) {
    }

    public function __invoke(Request $request, Brand $brand): Response
    {
        $updateBrandModel = UpdateBrandModel::fromBrand($brand);
        $form = $this->createForm(UpdateBrandForm::class, $updateBrandModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->messageBus->dispatch(new UpdateBrandCommand($brand->id, $updateBrandModel->name));

                return $this->redirectToRoute('app.brand.list');
            } catch (BrandAlreadyExistsException $exception) {
                $form->get('name')->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render('Food/update_brand.html.twig', [
            'form' => $form,
            'brand' => $brand,
        ]);
    }
}
