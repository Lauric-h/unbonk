<?php

namespace App\Tests\Unit\Infrastructure\Race\DTO;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Infrastructure\Race\DTO\CheckpointDTO;
use PHPUnit\Framework\TestCase;

final class CheckpointDTOTest extends TestCase
{
    public function testFromDomain(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000)),
            $race
        );
        $race->addCheckpoint($checkpoint);

        $expected = new CheckpointDTO(
            'cpId',
            'name',
            'location',
            10,
            120,
            1000,
            1000
        );

        $actual = CheckpointDTO::fromDomain($checkpoint);

        $this->assertEquals($expected, $actual);
    }
}
