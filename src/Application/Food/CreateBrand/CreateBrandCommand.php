<?php

namespace App\Application\Food\CreateBrand;

final readonly class CreateBrandCommand
{
    public function __construct(public string $name)
    {
    }
}