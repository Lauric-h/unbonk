<?php

namespace App\Tests\Unit\Domain\NutritionPlan\Service;

use App\Domain\NutritionPlan\Exception\ForbiddenNutritionPlanAccessException;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Service\NutritionPlanAccessService;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class NutritionPlanAccessServiceTest extends TestCase
{
    private MockObject&NutritionPlansCatalog $repository;
    private NutritionPlanAccessService $service;

    public function setUp(): void
    {
        $this->service = new NutritionPlanAccessService(
            $this->repository = $this->createMock(NutritionPlansCatalog::class),
        );
    }

    public function testCanAccess(): void
    {
        $nutritionPlan = (new NutritionPlanTestFixture())
            ->withId('np-id')
            ->withRunnerId('runnerId')
            ->build();

        $this->repository->expects($this->once())
            ->method('get')
            ->with('np-id')
            ->willReturn($nutritionPlan);

        $this->service->checkAccess('np-id', 'runnerId');
    }

    public function testCannotAccess(): void
    {
        $nutritionPlan = (new NutritionPlanTestFixture())
            ->withId('np-id')
            ->withRunnerId('runnerId')
            ->build();

        $this->repository->expects($this->once())
            ->method('get')
            ->with('np-id')
            ->willReturn($nutritionPlan);

        $this->expectException(ForbiddenNutritionPlanAccessException::class);
        $this->expectExceptionMessage('Runner otherRunner cannot access nutrition plan np-id');

        $this->service->checkAccess('np-id', 'otherRunner');
    }
}
