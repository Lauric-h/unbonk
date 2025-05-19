<?php

namespace App\Domain\User\Port;

interface PasswordServicePort
{
    public function hash(string $password): string;
}
