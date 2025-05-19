<?php

namespace App\Application\User\RegisterUser;

use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Port\PasswordServicePort;
use App\Domain\User\Repository\UserCatalog;
use App\SharedKernel\IdGenerator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
final readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserCatalog $userCatalog,
        private IdGenerator $idGenerator,
        private PasswordServicePort $passwordService,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        if (true === $this->userCatalog->userExists($command->username, $command->email)) {
            throw new UserAlreadyExistsException();
        }

        $user = new User(
            id: $this->idGenerator->generate(),
            username: $command->username,
            email: $command->email,
            password: $this->passwordService->hash($command->password),
        );

        $this->userCatalog->add($user);
    }
}
