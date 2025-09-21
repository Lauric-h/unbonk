<?php

namespace App\Domain\Shared;

interface IdGeneratorInterface
{
    public function generate(): string;
}
