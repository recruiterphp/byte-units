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

    /**
     * @param int $numberOf
     */
    public static function kilobytes($numberOf): self
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'kB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Metric
     */
    public static function megabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'MB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Metric
     */
    public static function gigabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'GB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Metric
     */
    public static function terabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'TB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Metric
     */
    public static function petabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'PB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Metric
     */
    public static function exabytes($numberOf)
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
