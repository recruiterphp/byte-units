<?php

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class MetricSystemTest extends TestCase
{
    public function testKilobytesConstructor()
    {
        $this->assertEquals(Metric::bytes(1000), Metric::kilobytes(1));
    }

    public function testMegabytesConstructor()
    {
        $this->assertEquals(Metric::bytes(1000000), Metric::megabytes(1));
    }

    public function testGigabytesConstructor()
    {
        $this->assertEquals(Metric::bytes(1000000000), Metric::gigabytes(1));
    }

    public function testTerabytesConstructor()
    {
        $this->assertEquals(Metric::bytes(1000000000000), Metric::terabytes(1));
    }

    public function testPetabytesConstructor()
    {
        $this->assertEquals(Metric::bytes(1000000000000000), Metric::petabytes(1));
    }

    public function testExabytesConstructor()
    {
        $this->assertEquals(Metric::bytes(1000000000000000000), Metric::exabytes(1));
    }

    public function testExabytesConstructorWithDecimal(): void
    {
        $this->assertEquals(Metric::bytes(1500000000000000000), Metric::exabytes(1.5));
    }

    public function testCannotBeNegative()
    {
        $this->expectException(NegativeBytesException::class);
        Metric::bytes(-1);
    }
}
