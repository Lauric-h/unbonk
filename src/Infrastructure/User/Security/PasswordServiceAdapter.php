<?php

namespace App\Infrastructure\User\Security;

use App\Domain\User\Port\PasswordServicePort;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class PasswordServiceAdapter implements PasswordServicePort
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hash(string $password): string
    {
        $passwordContainer = new class($password) implements PasswordAuthenticatedUserInterface {
            public function __construct(
                private string $password, // @phpstan-ignore-line
            ) {
            }

            public function getPassword(): string
            {
                return '';
            }
        };

        return $this->passwordHasher->hashPassword($passwordContainer, $password);
    }
}
