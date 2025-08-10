<?php

namespace ByteUnits;

class Metric extends System
{
    private const array SUFFIXES = [
        'YB' => 8,
        'ZB' => 7,
        'EB' => 6,
        'PB' => 5,
        'TB' => 4,
        'GB' => 3,
        'MB' => 2,
        'kB' => 1,
        'B' => 0,
    ];
    private const int BASE = 1000;

    private static ?PowerScale $scale = null;
    private static ?Parser $parser = null;

    public function __construct($numberOfBytes, $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION)
    {
        parent::__construct($numberOfBytes, new Formatter(self::scale(), $formatWithPrecision));
    }

    public static function kilobytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'kB'));
    }

    public static function megabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'MB'));
    }

    public static function gigabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'GB'));
    }

    public static function terabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'TB'));
    }

    public static function petabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'PB'));
    }

    public static function exabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'EB'));
    }

    public static function scale(): PowerScale
    {
        return self::$scale = self::$scale ?: new PowerScale(self::BASE, self::SUFFIXES, self::COMPUTE_WITH_PRECISION);
    }

    public static function parser(): Parser
    {
        return self::$parser = self::$parser ?: new Parser(self::scale(), self::class);
    }

    public function asBinary(): Binary
    {
        return Binary::bytes($this->numberOfBytes);
    }

    public function asMetric(): Metric
    {
        return $this;
    }
}
