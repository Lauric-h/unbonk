<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\ReadModel\External;

use App\Application\NutritionPlan\ReadModel\External\ExternalEventReadModel;
use App\Application\NutritionPlan\ReadModel\External\ExternalRaceListItemReadModel;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use PHPUnit\Framework\TestCase;

final class ExternalEventReadModelTest extends TestCase
{
    public function testFromDTO(): void
    {
        $eventDate = new \DateTimeImmutable('2024-06-15');

        $raceDTO = new ExternalRaceDTO(
            id: 'race-1',
            eventId: 'event-1',
            name: 'UTMB 100',
            distance: 171000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-06-15 06:00:00'),
            url: 'https://utmb.world/race',
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            aidStations: [],
        );

        $dto = new ExternalEventDTO(
            id: 'event-1',
            name: 'UTMB Mont-Blanc',
            location: 'Chamonix, France',
            date: $eventDate,
            url: 'https://utmb.world',
            races: [$raceDTO],
        );

        $readModel = ExternalEventReadModel::fromDTO($dto);

        $this->assertSame('event-1', $readModel->id);
        $this->assertSame('UTMB Mont-Blanc', $readModel->name);
        $this->assertSame('Chamonix, France', $readModel->location);
        $this->assertSame($eventDate, $readModel->date);
        $this->assertSame('https://utmb.world', $readModel->url);
        $this->assertCount(1, $readModel->races);
        $this->assertContainsOnlyInstancesOf(ExternalRaceListItemReadModel::class, $readModel->races);
    }
}
