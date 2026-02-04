<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\ReadModel\External;

use App\Application\NutritionPlan\ReadModel\External\ExternalAidStationReadModel;
use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use PHPUnit\Framework\TestCase;

final class ExternalAidStationReadModelTest extends TestCase
{
    public function testFromDTO(): void
    {
        $cutoffTime = new \DateTimeImmutable('2024-06-15 12:00:00');

        $dto = new ExternalAidStationDTO(
            id: 'aid-station-1',
            name: 'CP1 - Arnouvaz',
            location: 'Arnouvaz',
            distanceFromStart: 15000,
            ascentFromStart: 1200,
            descentFromStart: 300,
            cutoffTime: $cutoffTime,
            assistanceAllowed: true,
        );

        $readModel = ExternalAidStationReadModel::fromDTO($dto);

        $this->assertSame('aid-station-1', $readModel->id);
        $this->assertSame('CP1 - Arnouvaz', $readModel->name);
        $this->assertSame('Arnouvaz', $readModel->location);
        $this->assertSame(15000, $readModel->distanceFromStart);
        $this->assertSame(1200, $readModel->ascentFromStart);
        $this->assertSame(300, $readModel->descentFromStart);
        $this->assertSame($cutoffTime, $readModel->cutoffTime);
        $this->assertTrue($readModel->assistanceAllowed);
    }
}
