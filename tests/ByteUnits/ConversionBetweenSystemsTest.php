<?php

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class ConversionBetweenSystemsTest extends TestCase
{
    public function testBytesAreInMetricStystem()
    {
        $this->assertInstanceOf(Metric::class, bytes(1));
    }

    public function testConvertFromMetricToBinarySystem()
    {
        $this->assertInstanceOf(Binary::class, Metric::bytes(1)->asBinary());
    }

    public function testConvertFromBinaryToMetricSystem()
    {
        $this->assertInstanceOf(Metric::class, Binary::bytes(1)->asMetric());
    }
}
