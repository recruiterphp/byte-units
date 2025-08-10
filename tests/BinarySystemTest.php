<?php

declare(strict_types=1);

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class BinarySystemTest extends TestCase
{
    public function testKilobytesConstructor(): void
    {
        $this->assertEquals(Binary::bytes(1024), Binary::kilobytes(1));
    }

    public function testMegabytesConstructor(): void
    {
        $this->assertEquals(Binary::bytes(1048576), Binary::megabytes(1));
    }

    public function testGigabytesConstructor(): void
    {
        $this->assertEquals(Binary::bytes(1073741824), Binary::gigabytes(1));
    }

    public function testTerabytesConstructor(): void
    {
        $this->assertEquals(Binary::bytes(1099511627776), Binary::terabytes(1));
    }

    public function testPetabytesConstructor(): void
    {
        $this->assertEquals(Binary::bytes(1125899906842624), Binary::petabytes(1));
    }

    public function testExabytesConstructor(): void
    {
        $this->assertEquals(Binary::bytes(1152921504606846976), Binary::exabytes(1));
    }

    public function testCannotBeNegative(): void
    {
        $this->expectException(NegativeBytesException::class);
        Binary::bytes(-1);
    }
}
