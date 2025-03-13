<?php

namespace App\Application\Food\UseCase\ListFood;

use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class ListFoodQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $brandId = null,
        public ?string $name = null,
        #[Assert\Choice(callback: [IngestionType::class, 'cases'])]
        public ?IngestionType $ingestionType = null,
    ) {
    }
}