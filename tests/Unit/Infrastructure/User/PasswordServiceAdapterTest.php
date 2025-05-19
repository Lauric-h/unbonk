<?php

namespace App\Tests\Unit\Infrastructure\User;

use App\Infrastructure\User\Security\PasswordServiceAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PasswordServiceAdapterTest extends TestCase
{
    public function testHash(): void
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $service = new PasswordServiceAdapter($passwordHasher);

        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashedPassword');

        $actual = $service->hash('password');
        $this->assertSame('hashedPassword', $actual);
    }
}
