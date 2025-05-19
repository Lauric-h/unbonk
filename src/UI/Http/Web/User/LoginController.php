<?php

namespace App\UI\Http\Web\User;

use App\UI\Http\Web\User\Form\Login\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/login', name: 'app.user.login')]
final class LoginController extends AbstractController
{
    public function __construct(private readonly AuthenticationUtils $authenticationUtils)
    {
    }

    public function __invoke(): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app.race.list');
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);

        return $this->render('User/login.html.twig', [
            'title' => 'Login',
            'error' => $error,
            'last_username' => $lastUsername,
            'form' => $form
        ]);
    }

}