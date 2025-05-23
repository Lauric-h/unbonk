<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\CreateFood\CreateFoodCommand;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\Food\Form\CreateFood\CreateFoodForm;
use App\UI\Http\Web\Food\Form\CreateFood\CreateFoodModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}/foods/new', name: 'app.brand.food.create')]
final class CreateFoodController extends AbstractController
{
    public function __construct(private readonly CommandBus $bus, private readonly IdGeneratorInterface $idGenerator)
    {
    }

    public function __invoke(Request $request, Brand $brand): Response
    {
        $createFoodModel = new CreateFoodModel();
        $form = $this->createForm(CreateFoodForm::class, $createFoodModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch(new CreateFoodCommand(
                id: $this->idGenerator->generate(),
                brandId: $brand->id,
                name: $createFoodModel->name, // @phpstan-ignore-line checks made in model
                carbs: $createFoodModel->carbs, // @phpstan-ignore-line checks made in model
                ingestionType: $createFoodModel->ingestionType->value, // @phpstan-ignore-line checks made in model
                calories: $createFoodModel->calories,
            ));

            return $this->redirectToRoute('app.food.list');
        }

        return $this->render('Food/create_food.html.twig', [
            'form' => $form,
            'brandName' => $brand->name,
        ]);
    }
}
