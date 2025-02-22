<?php

namespace App\Domain\Food;

final class Brand
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}