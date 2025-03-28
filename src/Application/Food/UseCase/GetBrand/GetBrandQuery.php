<?php

namespace App\Application\Food\UseCase\GetBrand;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetBrandQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
