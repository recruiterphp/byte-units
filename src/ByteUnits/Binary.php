<?php

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
    private const int BASE = 1024;

    private static ?PowerScale $scale = null;
    private static $parser;

    /**
     * @param int $numberOf
     *
     * @return Binary
     */
    public static function kilobytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'KiB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Binary
     */
    public static function megabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'MiB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Binary
     */
    public static function gigabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'GiB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Binary
     */
    public static function terabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'TiB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Binary
     */
    public static function petabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'PiB'));
    }

    /**
     * @param int $numberOf
     *
     * @return Binary
     */
    public static function exabytes($numberOf)
    {
        return new self(self::scale()->scaleFromUnit($numberOf, 'EiB'));
    }

    /**
     * @param int $numberOfBytes
     *
     * @return Binary
     */
    public function __construct($numberOfBytes, $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION)
    {
        parent::__construct($numberOfBytes, new Formatter(self::scale(), $formatWithPrecision));
    }

    /**
     * @return PowerScale
     */
    public static function scale()
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
