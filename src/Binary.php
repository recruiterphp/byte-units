<?php

declare(strict_types=1);

namespace ByteUnits;

final class Binary extends System
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

    /**
     * @var ?Parser<self>
     */
    private static ?Parser $parser = null;

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function bytes(float|int|string $numberOf, int $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION): static
    {
        return new self($numberOf, new Formatter(self::scale(), $formatWithPrecision));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function kilobytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'KiB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function megabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'MiB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function gigabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'GiB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function terabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'TiB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function petabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'PiB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function exabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'EiB'));
    }

    public static function scale(): PowerScale
    {
        return self::$scale = self::$scale ?: new PowerScale(self::BASE, self::SUFFIXES, self::COMPUTE_WITH_PRECISION);
    }

    public static function parser(): Parser
    {
        return self::$parser = self::$parser ?: new Parser(self::scale(), self::bytes(...));
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
