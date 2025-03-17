<?php

namespace App\Domain\User\Entity;

final class User
{
    public function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $password,
    ) {
    }
}
