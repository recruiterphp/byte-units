<?php

declare(strict_types=1);

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class MetricSystemTest extends TestCase
{
    public function testKilobytesConstructor(): void
    {
        $this->assertEquals(Metric::bytes(1000), Metric::kilobytes(1));
    }

    public function testMegabytesConstructor(): void
    {
        $this->assertEquals(Metric::bytes(1000000), Metric::megabytes(1));
    }

    public function testGigabytesConstructor(): void
    {
        $this->assertEquals(Metric::bytes(1000000000), Metric::gigabytes(1));
    }

    public function testTerabytesConstructor(): void
    {
        $this->assertEquals(Metric::bytes(1000000000000), Metric::terabytes(1));
    }

    public function testPetabytesConstructor(): void
    {
        $this->assertEquals(Metric::bytes(1000000000000000), Metric::petabytes(1));
    }

    public function testExabytesConstructor(): void
    {
        $this->assertEquals(Metric::bytes(1000000000000000000), Metric::exabytes(1));
    }

    public function testExabytesConstructorWithDecimal(): void
    {
        $this->assertEquals(Metric::bytes(1500000000000000000), Metric::exabytes(1.5));
    }

    public function testCannotBeNegative(): void
    {
        $this->expectException(NegativeBytesException::class);
        Metric::bytes(-1);
    }
}
