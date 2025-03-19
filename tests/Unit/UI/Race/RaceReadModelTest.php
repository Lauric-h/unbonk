<?php

namespace App\Tests\Unit\UI\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\UI\Http\Rest\Race\View\AddressReadModel;
use App\UI\Http\Rest\Race\View\ProfileReadModel;
use App\UI\Http\Rest\Race\View\RaceReadModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class RaceReadModelTest extends TestCase
{
    public function testFromRace(): void
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

        $expected = new RaceReadModel(
            'id',
            $date->format('Y-m-d'),
            'Le Bélier',
            new ProfileReadModel(42, 2000, 2000),
            new AddressReadModel('La Clusaz', '74xxx'),
            'runner-id'
        );

        $actual = RaceReadModel::fromRace($race);

        $this->assertEquals($expected, $actual);
    }
}
