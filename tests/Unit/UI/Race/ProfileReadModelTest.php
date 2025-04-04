<?php

namespace App\Tests\Unit\UI\Race;

use App\Domain\Race\Entity\Profile;
use App\UI\Http\Rest\Race\View\ProfileReadModel;
use PHPUnit\Framework\TestCase;

final class ProfileReadModelTest extends TestCase
{
    public function testFromDomain(): void
    {
        $domain = new Profile(42, 2000, 2500);

        $expected = new ProfileReadModel(42, 2000, 2500);

        $actual = ProfileReadModel::fromDomain($domain);

        $this->assertEquals($expected, $actual);
    }
}
