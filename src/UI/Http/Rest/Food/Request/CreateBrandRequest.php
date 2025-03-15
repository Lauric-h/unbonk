<?php

namespace App\UI\Http\Rest\Food\Request;

final readonly class CreateBrandRequest
{
    public function __construct(public string $name)
    {
    }
}
