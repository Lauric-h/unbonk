<?php

namespace App\Tests\Unit\Application\User;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Application\User\RegisterUser\RegisterUserCommandHandler;
use App\Domain\Shared\IdGeneratorInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Port\PasswordServicePort;
use App\Domain\User\Repository\UserCatalog;
use PHPUnit\Framework\TestCase;

final class RegisterUserCommandHandlerTest extends TestCase
{
    public function testRegisterUser(): void
    {
        $repository = $this->createMock(UserCatalog::class);
        $idGenerator = $this->createMock(IdGeneratorInterface::class);
        $passwordService = $this->createMock(PasswordServicePort::class);

        $handler = new RegisterUserCommandHandler($repository, $idGenerator, $passwordService);

        $repository->expects($this->once())
            ->method('userExists')
            ->with('name', 'email')
            ->willReturn(false);

        $idGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('id');

        $passwordService->expects($this->once())
            ->method('hash')
            ->with('password')
            ->willReturn('hashed-password');

        $repository->expects($this->once())
            ->method('add')
            ->with(new User('id', 'name', 'email', 'hashed-password'));

        ($handler)(new RegisterUserCommand('name', 'email', 'password'));
    }

    public function testRegisterNonUniqueUserThrowsException(): void
    {
        $repository = $this->createMock(UserCatalog::class);
        $idGenerator = $this->createMock(IdGeneratorInterface::class);
        $passwordService = $this->createMock(PasswordServicePort::class);

        $handler = new RegisterUserCommandHandler($repository, $idGenerator, $passwordService);

        $repository->expects($this->once())
            ->method('userExists')
            ->with('name', 'email')
            ->willReturn(true);

        $this->expectException(UserAlreadyExistsException::class);

        ($handler)(new RegisterUserCommand('name', 'email', 'password'));
    }
}
