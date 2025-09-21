<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\CreateBrand\CreateBrandCommand;
use App\Domain\Food\Exception\BrandAlreadyExistsException;
use App\Domain\Shared\IdGeneratorInterface;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\Food\Form\CreateBrand\CreateBrandForm;
use App\UI\Http\Web\Food\Form\CreateBrand\CreateBrandModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/new', name: 'app.brand.create')]
final class CreateBrandController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $messageBus,
        private readonly IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $createBrandModel = new CreateBrandModel();
        $form = $this->createForm(CreateBrandForm::class, $createBrandModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->messageBus->dispatch(new CreateBrandCommand(
                    id: $this->idGenerator->generate(),
                    name: $createBrandModel->name // @phpstan-ignore-line check is made in model
                ));

                return $this->redirectToRoute('app.brand.list');
            } catch (BrandAlreadyExistsException $exception) {
                $form->get('name')->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render('Food/create_brand.html.twig', [
            'form' => $form,
        ]);
    }
}
