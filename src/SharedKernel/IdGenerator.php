<?php

namespace App\SharedKernel;

use Symfony\Component\Uid\Uuid;

final class IdGenerator
{
    public function generate(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
