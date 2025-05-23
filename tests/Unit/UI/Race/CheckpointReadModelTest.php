<?php

namespace App\Tests\Unit\UI\Race;

use App\Application\Race\ReadModel\CheckpointReadModel;
use App\Application\Race\ReadModel\MetricsFromStartReadModel;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use PHPUnit\Framework\TestCase;

final class CheckpointReadModelTest extends TestCase
{
    public function testFromCheckpoint(): void
    {
        $date = new \DateTimeImmutable('2025-01-01');
        $race = Race::create(
            'id1',
            $date,
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $expected = new CheckpointReadModel(
            'startId',
            'start',
            'La Clusaz',
            CheckpointType::Start->value,
            new MetricsFromStartReadModel(0, 0, 0, 0)
        );

        $actual = CheckpointReadModel::fromCheckpoint($race->getStartCheckpoint());

        $this->assertEquals($expected, $actual);
    }
}
