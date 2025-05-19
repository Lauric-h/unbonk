<?php

namespace App\Application\User\RegisterUser;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Port\PasswordServicePort;
use App\Domain\User\Repository\UserCatalog;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserCatalog $userCatalog,
        private IdGeneratorInterface $idGenerator,
        private PasswordServicePort $passwordService,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        /* @phpstan-ignore-next-line */
        if (true === $this->userCatalog->userExists($command->username, $command->email)) {
            throw new UserAlreadyExistsException();
        }

        // Phpstan ignore - checks are made with \Assert in model
        $user = new User(
            id: $this->idGenerator->generate(),
            username: $command->username, // @phpstan-ignore-line
            email: $command->email, // @phpstan-ignore-line
            password: $this->passwordService->hash($command->password), // @phpstan-ignore-line
        );

        $this->userCatalog->add($user);
    }
}
