<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\Race\Entity\Segment;
use App\Domain\Race\Exception\SegmentNotFoundException;
use App\Domain\Race\Repository\SegmentsCatalog;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineNutritionSegmentsCatalog
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }
}
