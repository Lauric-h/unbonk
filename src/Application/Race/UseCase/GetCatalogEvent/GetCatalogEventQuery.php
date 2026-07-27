<?php

namespace App\Application\Race\UseCase\GetCatalogEvent;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetCatalogEventQuery implements QueryInterface
{
    public function __construct(public string $id)
    {
    }
}