<?php

namespace App\Tests\Unit\UI\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\UI\Http\Rest\Race\View\CheckpointReadModel;
use App\UI\Http\Rest\Race\View\MetricsFromStartReadModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class CheckpointReadModelTest extends TestCase
{
    public function testFromCheckpoint(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint = new Checkpoint(
            'id1',
            'name1',
            'location1',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $expected = new CheckpointReadModel(
            'id1',
            'name1',
            'location1',
            CheckpointType::Start->value,
            new MetricsFromStartReadModel(0, 0, 0, 0)
        );

        $actual = CheckpointReadModel::fromCheckpoint($checkpoint);

        $this->assertEquals($expected, $actual);
    }
}
