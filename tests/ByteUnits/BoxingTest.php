<?php

declare(strict_types=1);

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class BoxingTest extends TestCase
{
    public function testBoxAnInteger(): void
    {
        $this->assertEquals(bytes(42), box(42));
    }

    public function testBoxAString(): void
    {
        $this->assertEquals(parse('1.256MB'), box('1.256MB'));
    }

    public function testBoxAByteUnit(): void
    {
        $byteUnitInMetricSystem = Metric::bytes(42);
        $byteUnitInBinarySystem = Binary::bytes(42);
        $this->assertEquals($byteUnitInMetricSystem, box($byteUnitInMetricSystem));
        $this->assertEquals($byteUnitInBinarySystem, box($byteUnitInBinarySystem));
    }
}
