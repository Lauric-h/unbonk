<?php

namespace App\Tests\Unit\Domain\NutritionPlan\Service;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Exception\ForbiddenRaceForRunnerException;
use App\Domain\NutritionPlan\Port\RaceOwnershipPort;
use App\Domain\NutritionPlan\Service\NutritionPlanAccessService;
use App\Infrastructure\NutritionPlan\Persistence\DoctrineNutritionPlansCatalog;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class NutritionPlanAccessServiceTest extends TestCase
{
    private MockObject&DoctrineNutritionPlansCatalog $repository;
    private MockObject&RaceOwnershipPort $raceOwnershipPort;
    private NutritionPlanAccessService $service;

    public function setUp(): void
    {
        $this->service = new NutritionPlanAccessService(
            $this->repository = $this->createMock(DoctrineNutritionPlansCatalog::class),
            $this->raceOwnershipPort = $this->createMock(RaceOwnershipPort::class)
        );
    }

    public function testCanAccess(): void
    {
        $nutritionPlan = new NutritionPlan(
            'np-id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $this->repository->expects($this->once())
            ->method('get')
            ->with('np-id')
            ->willReturn($nutritionPlan);

        $this->raceOwnershipPort->expects($this->once())
            ->method('userOwnsRace')
            ->with('raceId', 'runnerId')
            ->willReturn(true);

        $this->service->checkAccess('np-id', 'runnerId');
    }

    public function testCannotAccess(): void
    {
        $nutritionPlan = new NutritionPlan(
            'np-id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $this->repository->expects($this->once())
            ->method('get')
            ->with('np-id')
            ->willReturn($nutritionPlan);

        $this->raceOwnershipPort->expects($this->once())
            ->method('userOwnsRace')
            ->with('raceId', 'runnerId')
            ->willReturn(false);

        $this->expectException(ForbiddenRaceForRunnerException::class);
        $this->expectExceptionMessage('Runner runnerId cannot access race raceId');

        $this->service->checkAccess('np-id', 'runnerId');
    }
}
