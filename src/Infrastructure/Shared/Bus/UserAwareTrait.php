<?php

namespace App\Infrastructure\Shared\Bus;


trait UserAwareTrait
{
    private ?string $userId = null;

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        if (null === $this->userId) {
            throw new \RuntimeException('User unknown');
        }

        return $this->userId;
    }
}