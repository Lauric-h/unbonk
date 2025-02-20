<?php

namespace App\Domain\Shared;

use Symfony\Component\Uid\Uuid;

final class IdGenerator
{
    public function generate(): Uuid
    {
        return Uuid::v7();
    }
}
