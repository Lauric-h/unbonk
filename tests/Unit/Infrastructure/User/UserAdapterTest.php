<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\User;

use App\Domain\User\Entity\User;
use App\Infrastructure\User\Security\UserAdapter;
use PHPUnit\Framework\TestCase;

final class UserAdapterTest extends TestCase
{
    public function testGetPassword(): void
    {
        $user = new User('id', 'name', 'email', 'password');
        $adapter = new UserAdapter($user);

        $this->assertSame($user->password, $adapter->getPassword());
    }

    public function testGetRoles(): void
    {
        $user = new User('id', 'name', 'email', 'password');
        $adapter = new UserAdapter($user);

        $this->assertSame(['ROLE_USER'], $adapter->getRoles());
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User('id', 'name', 'email', 'password');
        $adapter = new UserAdapter($user);

        $this->assertSame($user->email, $adapter->getUserIdentifier());
    }

    public function testGetUserIdentifierEmptyStringThrowsException(): void
    {
        $user = new User('id', 'name', '', 'password');
        $adapter = new UserAdapter($user);

        $this->expectException(\LogicException::class);
        $adapter->getUserIdentifier();
    }

    public function testGetUser(): void
    {
        $user = new User('id', 'name', 'email', 'password');
        $adapter = new UserAdapter($user);

        $this->assertSame($user, $adapter->getUser());
    }
}
