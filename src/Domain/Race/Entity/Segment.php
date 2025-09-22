<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

class Segment
{
    private ?int $id = null; // Let Persistence generate ID

    public function __construct(
        public Checkpoint $start,
        public Checkpoint $finish,
        public int $position,
        public Distance $distance,
        public Ascent $ascent,
        public Descent $descent,
        public Duration $estimatedTimeInMinutes,
    ) {
    }

    public static function createFromCheckpoints(Checkpoint $start, Checkpoint $finish, int $position): Segment
    {
        return new self(
            start: $start,
            finish: $finish,
            position: $position,
            distance: self::computeDistance($start->getMetricsFromStart()->distance, $finish->getMetricsFromStart()->distance),
            ascent: self::computeAscent($start->getMetricsFromStart()->ascent, $finish->getMetricsFromStart()->ascent),
            descent: self::computeDescent($start->getMetricsFromStart()->descent, $finish->getMetricsFromStart()->descent),
            estimatedTimeInMinutes: self::computeDuration($start->getMetricsFromStart()->estimatedTimeInMinutes, $finish->getMetricsFromStart()->estimatedTimeInMinutes),
        );
    }

    public function getId(): int
    {
        if (null === $this->id) {
            throw new \DomainException('ID cannot be null');
        }

        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    private static function computeDistance(int $start, int $finish): Distance
    {
        return new Distance($finish - $start);
    }

    private static function computeAscent(int $start, int $finish): Ascent
    {
        return new Ascent($finish - $start);
    }

    private static function computeDescent(int $start, int $finish): Descent
    {
        return new Descent($finish - $start);
    }

    private static function computeDuration(int $start, int $finish): Duration
    {
        return new Duration($finish - $start);
    }

    public function getRace(): Race
    {
        return $this->start->getRace();
    }
}
