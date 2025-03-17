<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserCatalog
{
    public function getByEmail(string $email): User;

    public function getById(string $id): User;
}
