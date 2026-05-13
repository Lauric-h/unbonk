<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\UpdateFood\UpdateFoodCommand;
use App\Domain\Food\Entity\Food;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\Food\Form\UpdateFood\UpdateFoodForm;
use App\UI\Http\Web\Food\Form\UpdateFood\UpdateFoodModel;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'app.food.update')]
final class UpdateFoodController extends AbstractController
{
    public function __construct(private readonly CommandBus $bus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Food $food,
        Request $request
    ): Response {
        $foodModel = UpdateFoodModel::fromFood($food);
        $form = $this->createForm(UpdateFoodForm::class, $foodModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch(new UpdateFoodCommand(
                id: $food->id,
                name: $foodModel->name,
                carbs: $foodModel->carbs,
                ingestionType: $foodModel->ingestionType,
                calories: $foodModel->calories
            ));

            return $this->redirectToRoute('app.food.list');
        }

        return $this->render('food/update_food.html.twig', [
            'food' => $foodModel,
            'form' => $form,
        ]);
    }
}
