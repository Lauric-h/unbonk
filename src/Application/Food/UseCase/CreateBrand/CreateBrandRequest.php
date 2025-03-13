<?php

namespace App\Application\Food\UseCase\CreateBrand;

final readonly class CreateBrandRequest
{
    public function __construct(public string $name)
    {
    }
}
