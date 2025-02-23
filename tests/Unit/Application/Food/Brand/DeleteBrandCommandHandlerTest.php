<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\DeleteBrand\DeleteBrandCommand;
use App\Application\Food\DeleteBrand\DeleteBrandCommandHandler;
use App\Infrastructure\Food\Persistence\DoctrineBrandsCatalog;
use PHPUnit\Framework\TestCase;

class DeleteBrandCommandHandlerTest extends TestCase
{
    public function testDeleteBrand(): void
    {
        $id = 'id';
        $repository = $this->createMock(DoctrineBrandsCatalog::class);
        $handler = new DeleteBrandCommandHandler($repository);
        $command = new DeleteBrandCommand($id);

        $repository->expects($this->once())
            ->method('remove')
            ->with($id);

        ($handler)($command);
    }
}
