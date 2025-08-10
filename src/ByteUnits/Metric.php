<?php

declare(strict_types=1);

namespace ByteUnits;

final class Metric extends System
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
    private const string BASE = '1000';

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
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'kB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function megabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'MB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function gigabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'GB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function terabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'TB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function petabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'PB'));
    }

    /**
     * @param float|int|numeric-string $numberOf
     */
    public static function exabytes(int|float|string $numberOf): self
    {
        return self::bytes(self::scale()->scaleFromUnit($numberOf, 'EB'));
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
        return Binary::bytes($this->numberOfBytes);
    }

    public function asMetric(): Metric
    {
        return $this;
    }
}
