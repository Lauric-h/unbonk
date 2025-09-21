<?php

namespace App\Tests\Unit\Domain\NutritionPlan\Service;

use App\Domain\NutritionPlan\Service\NutritionPlanAccessService;
use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Exception\ForbiddenRaceForRunnerException;
use App\Domain\Race\Port\RaceOwnershipPort;
use App\Infrastructure\Race\Persistence\Repository\DoctrineNutritionPlansCatalog;
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
