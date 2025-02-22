<?php

namespace App\Application\Food\CreateBrand;

final readonly class CreateBrandRequest
{
    public function __construct(public string $name)
    {
    }
}
