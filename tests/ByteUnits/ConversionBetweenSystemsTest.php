<?php

declare(strict_types=1);

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class ConversionBetweenSystemsTest extends TestCase
{
    public function testBytesAreInMetricStystem(): void
    {
        $this->assertInstanceOf(Metric::class, bytes(1));
    }

    public function testConvertFromMetricToBinarySystem(): void
    {
        $this->assertInstanceOf(Binary::class, Metric::bytes(1)->asBinary());
    }

    public function testConvertFromBinaryToMetricSystem(): void
    {
        $this->assertInstanceOf(Metric::class, Binary::bytes(1)->asMetric());
    }
}
