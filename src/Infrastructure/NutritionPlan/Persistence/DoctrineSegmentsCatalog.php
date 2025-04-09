<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Exception\SegmentNotFoundException;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSegmentsCatalog implements SegmentsCatalog
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function get(string $id): Segment
    {
        $segment = $this->entityManager->find(Segment::class, $id);
        if (null === $segment) {
            throw new SegmentNotFoundException($id);
        }

        return $segment;
    }

    public function add(Segment $segment): void
    {
        $this->entityManager->persist($segment);
    }
}
