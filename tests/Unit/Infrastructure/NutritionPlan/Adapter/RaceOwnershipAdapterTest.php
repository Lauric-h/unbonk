<?php

namespace App\Tests\Unit\Infrastructure\NutritionPlan\Adapter;

use App\Application\Race\Service\RaceAccessPort;
use App\Infrastructure\NutritionPlan\Adapter\RaceOwnershipAdapter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RaceOwnershipAdapterTest extends TestCase
{
    #[DataProvider('provide')]
    public function testItCallsRaceAccessPort(bool $expected): void
    {
        $raceAccessPort = $this->createMock(RaceAccessPort::class);
        $raceOwnershipAdapter = new RaceOwnershipAdapter($raceAccessPort);

        $raceAccessPort->expects($this->once())
            ->method('checkAccess')
            ->with('raceId', 'runnerId')
            ->willReturn($expected);

        $actual = $raceOwnershipAdapter->userOwnsRace('raceId', 'runnerId');

        $this->assertSame($expected, $actual);
    }

    public static function provide(): \Generator
    {
        yield ['expected' => true];
        yield ['expected' => false];
    }
}
