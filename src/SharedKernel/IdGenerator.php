<?php

namespace App\SharedKernel;

use App\Application\Shared\IdGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class IdGenerator implements IdGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
