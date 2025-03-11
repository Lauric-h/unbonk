<?php

namespace App\Tests\Unit\Application\Food\Food;

use App\Application\Food\DeleteFood\DeleteFoodCommand;
use App\Application\Food\DeleteFood\DeleteFoodCommandHandler;
use App\Infrastructure\Food\Persistence\DoctrineFoodsCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteFoodCommandHandlerTest extends TestCase
{
    public function testDeleteFood(): void
    {
        $id = 'id';
        $repository = $this->createMock(DoctrineFoodsCatalog::class);
        $handler = new DeleteFoodCommandHandler($repository);
        $command = new DeleteFoodCommand($id);

        $repository->expects($this->once())
            ->method('remove')
            ->with($id);

        ($handler)($command);
    }
}
