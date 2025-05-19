<?php

namespace App\UI\Http\Web\User;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\User\Form\Register\RegisterModel;
use App\UI\Http\Web\User\Form\Register\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register', name: 'app.user.register')]
final class RegisterController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $registerModel = new RegisterModel();
        $form = $this->createForm(RegisterType::class, $registerModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->dispatch(new RegisterUserCommand(
                    $registerModel->username,
                    $registerModel->email,
                    $registerModel->password,
                ));
            } catch (UserAlreadyExistsException $e) {
                return $this->render('User/register.html.twig', [
                    'form' => $form,
                    'hasError' => true,
                ]);
            }

            return $this->redirectToRoute('app.user.login');
        }

        return $this->render('User/register.html.twig', [
            'form' => $form,
            'hasError' => false,
        ]);
    }
}
