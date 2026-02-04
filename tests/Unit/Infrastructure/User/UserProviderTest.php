<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\User;

use App\Domain\User\Entity\User;
use App\Infrastructure\User\Persistence\DoctrineUserCatalog;
use App\Infrastructure\User\Security\UserAdapter;
use App\Infrastructure\User\Security\UserProvider;
use PHPUnit\Framework\TestCase;

final class UserProviderTest extends TestCase
{
    public function testRefreshUser(): void
    {
        $repository = $this->createMock(DoctrineUserCatalog::class);
        $user = new User('id', 'name', '', 'password');
        $provider = new UserProvider($repository);

        $repository->expects($this->once())
            ->method('getById')
            ->with('id')
            ->willReturn($user);

        $expected = new UserAdapter($user);
        $actual = $provider->refreshUser($expected);

        $this->assertEquals($expected, $actual);
    }

    public function testSupportsClass(): void
    {
        $class = UserAdapter::class;
        $provider = new UserProvider($this->createMock(DoctrineUserCatalog::class));

        $this->assertTrue($provider->supportsClass($class));
    }

    public function testDoesNotSupportsClass(): void
    {
        $class = User::class;
        $provider = new UserProvider($this->createMock(DoctrineUserCatalog::class));

        $this->assertFalse($provider->supportsClass($class));
    }

    public function testLoadUserByIdentifier(): void
    {
        $repository = $this->createMock(DoctrineUserCatalog::class);
        $user = new User('id', 'name', 'email', 'password');
        $provider = new UserProvider($repository);

        $repository->expects($this->once())
            ->method('getByEmail')
            ->with('email')
            ->willReturn($user);

        $expected = new UserAdapter($user);
        $actual = $provider->loadUserByIdentifier('email');

        $this->assertEquals($expected, $actual);
    }
}
