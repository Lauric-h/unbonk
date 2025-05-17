<?php

namespace App\Tests\Unit\Infrastructure\Race\Service;

use App\Domain\Race\Exception\RaceNotFoundException;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\Infrastructure\Race\Service\RaceAccessAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RaceAccessAdapterTest extends TestCase
{
    private MockObject&DoctrineRacesCatalog $repository;
    private RaceAccessAdapter $adapter;

    public function setUp(): void
    {
        $this->adapter = new RaceAccessAdapter(
            $this->repository = $this->createMock(DoctrineRacesCatalog::class)
        );
    }

    public function testCanAccess(): void
    {
        $this->repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('id', 'runner-id');

        $this->assertTrue($this->adapter->checkAccess('id', 'runner-id'));
    }

    public function testCannotAccess(): void
    {
        $this->repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('id', 'runner-id')
            ->willThrowException(new RaceNotFoundException());

        $this->assertFalse($this->adapter->checkAccess('id', 'runner-id'));
    }
}
