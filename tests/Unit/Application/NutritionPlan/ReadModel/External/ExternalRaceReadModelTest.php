<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\ReadModel\External;

use App\Application\NutritionPlan\ReadModel\External\ExternalAidStationReadModel;
use App\Application\NutritionPlan\ReadModel\External\ExternalRaceReadModel;
use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use PHPUnit\Framework\TestCase;

final class ExternalRaceReadModelTest extends TestCase
{
    public function testFromDTO(): void
    {
        $startDateTime = new \DateTimeImmutable('2024-06-15 06:00:00');

        $aidStationDTO = new ExternalAidStationDTO(
            id: 'aid-1',
            name: 'CP1',
            location: 'Location1',
            distanceFromStart: 10000,
            ascentFromStart: 500,
            descentFromStart: 100,
            cutoffTime: null,
            assistanceAllowed: true,
        );

        $dto = new ExternalRaceDTO(
            id: 'race-1',
            eventId: 'event-1',
            name: 'UTMB 100',
            distance: 171000,
            ascent: 10000,
            descent: 10000,
            startDateTime: $startDateTime,
            url: 'https://utmb.world',
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            aidStations: [$aidStationDTO],
        );

        $readModel = ExternalRaceReadModel::fromDTO($dto);

        $this->assertSame('race-1', $readModel->id);
        $this->assertSame('event-1', $readModel->eventId);
        $this->assertSame('UTMB 100', $readModel->name);
        $this->assertSame(171000, $readModel->distance);
        $this->assertSame(10000, $readModel->ascent);
        $this->assertSame(10000, $readModel->descent);
        $this->assertSame($startDateTime, $readModel->startDateTime);
        $this->assertSame('https://utmb.world', $readModel->url);
        $this->assertSame('Chamonix', $readModel->startLocation);
        $this->assertSame('Chamonix', $readModel->finishLocation);
        $this->assertCount(1, $readModel->aidStations);
        $this->assertContainsOnlyInstancesOf(ExternalAidStationReadModel::class, $readModel->aidStations);
    }
}
