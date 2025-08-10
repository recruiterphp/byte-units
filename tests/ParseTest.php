<?php

declare(strict_types=1);

namespace ByteUnits;

use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{
    public function testParseInMetricSystem(): void
    {
        $this->assertEquals(Metric::bytes(1000), parse('1.00kB'));
        $this->assertEquals(Metric::bytes(1250000), parse('1.25MB'));
    }

    public function testParseInBinarySystem(): void
    {
        $this->assertEquals(Binary::bytes(1024), parse('1.00KiB'));
        $this->assertEquals(Binary::bytes(1310720), parse('1.25MiB'));
    }

    public function testParseWithoutUnit(): void
    {
        $this->assertEquals(Metric::bytes(1000), parse('1000'));
    }

    public function testParseWithSeparator(): void
    {
        $this->assertEquals(Metric::bytes(1000), parse('1.00 kB'));
        $this->assertEquals(Metric::bytes(1000), parse('1.00/kB'));
        $this->assertEquals(Metric::bytes(1000), parse('1.00~~~kB'));
    }

    public function testInvalidByteFormat(): void
    {
        $this->expectException(ParseException::class);
        parse('Not a valid byte format');
    }

    public function testInvalidByteFormatForBinarySystem(): void
    {
        $this->expectException(ParseException::class);
        Binary::parse('1.00kB');
    }

    public function testInvalidByteFormatForMetricSystem(): void
    {
        $this->expectException(ParseException::class);
        Metric::parse('1.00KiB');
    }
}
