<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\ReadModel\External;

use App\Application\NutritionPlan\ReadModel\External\ExternalRaceListItemReadModel;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use PHPUnit\Framework\TestCase;

final class ExternalRaceListItemReadModelTest extends TestCase
{
    public function testFromDTO(): void
    {
        $startDateTime = new \DateTimeImmutable('2024-06-15 06:00:00');

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
            aidStations: [],
        );

        $readModel = ExternalRaceListItemReadModel::fromDTO($dto);

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
    }
}
