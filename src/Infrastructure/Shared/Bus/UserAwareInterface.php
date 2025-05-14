<?php

namespace App\Infrastructure\Shared\Bus;

interface UserAwareInterface
{
    public function setUserId(string $userId): void;
    public function getUserId(): ?string;
}