<?php

declare(strict_types=1);

namespace ByteUnits;

class Binary extends System
{
    private const array SUFFIXES = [
        'YiB' => 8,
        'ZiB' => 7,
        'EiB' => 6,
        'PiB' => 5,
        'TiB' => 4,
        'GiB' => 3,
        'MiB' => 2,
        'KiB' => 1,
        'B' => 0,
    ];
    private const string BASE = '1024';

    private static ?PowerScale $scale = null;
    private static ?Parser $parser = null;

    public static function kilobytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'KiB'));
    }

    public static function megabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'MiB'));
    }

    public static function gigabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'GiB'));
    }

    public static function terabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'TiB'));
    }

    public static function petabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'PiB'));
    }

    public static function exabytes(int|float|string $numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'EiB'));
    }

    public function __construct(int|float|string $numberOfBytes, int $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION)
    {
        parent::__construct($numberOfBytes, new Formatter(self::scale(), $formatWithPrecision));
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
        return $this;
    }

    public function asMetric(): Metric
    {
        return Metric::bytes($this->numberOfBytes);
    }
}
