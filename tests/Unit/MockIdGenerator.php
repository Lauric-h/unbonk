<?php

namespace App\Tests\Unit;

use App\Domain\Shared\IdGeneratorInterface;

final class MockIdGenerator implements IdGeneratorInterface
{
    public function __construct(
        public string $id,
    ) {
    }

    public function generate(): string
    {
        return $this->id;
    }
}
