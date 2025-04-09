<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Exception\SegmentNotFoundException;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSegmentsCatalog implements SegmentsCatalog
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

    public function getByNutritionPlanAndId(string $nutritionPlanId, string $segmentId): Segment
    {
        $segment = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(Segment::class, 's')
            ->where('s.nutritionPlanId = :nutritionPlanId')
            ->andWhere('s.segmentId = :segmentId')
            ->setParameter('nutritionPlanId', $nutritionPlanId)
            ->setParameter('segmentId', $segmentId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $segment) {
            throw new SegmentNotFoundException($segmentId);
        }

        return $segment;
    }
}
