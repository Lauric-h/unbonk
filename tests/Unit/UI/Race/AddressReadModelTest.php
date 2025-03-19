<?php

namespace App\Tests\Unit\UI\Race;

use App\Domain\Race\Entity\Address;
use App\UI\Http\Rest\Race\View\AddressReadModel;
use PHPUnit\Framework\TestCase;

final class AddressReadModelTest extends TestCase
{
    public function testFromDomain(): void
    {
        $domain = new Address('city', 'postalCode');

        $expected = new AddressReadModel('city', 'postalCode');

        $actual = AddressReadModel::fromDomain($domain);

        $this->assertEquals($expected, $actual);
    }
}
