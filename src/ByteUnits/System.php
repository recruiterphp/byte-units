<?php

namespace ByteUnits;

abstract class System
{
    public const DEFAULT_FORMAT_PRECISION = 2;
    public const COMPUTE_WITH_PRECISION = 10;

    protected $formatter;
    protected string $numberOfBytes;

    public static function bytes(int|string $numberOf, int $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION): static
    {
        return new static($numberOf, $formatWithPrecision);
    }

    public static function parse(string $bytesAsString)
    {
        return static::parser()->parse($bytesAsString);
    }

    public function __construct(int|string $numberOfBytes, $formatter)
    {
        $this->formatter = $formatter;
        $this->numberOfBytes = $this->ensureIsNotNegative($this->normalize($numberOfBytes));
    }

    public function add(int|string|System $another): static
    {
        return new static(
            bcadd($this->numberOfBytes, box($another)->numberOfBytes, self::COMPUTE_WITH_PRECISION),
            $this->formatter->precision(),
        );
    }

    public function remove(int|string|System $another): static
    {
        return new static(
            bcsub($this->numberOfBytes, box($another)->numberOfBytes, self::COMPUTE_WITH_PRECISION),
            $this->formatter->precision(),
        );
    }

    /**
     * @param System $another
     *
     * @return bool
     */
    public function isEqualTo($another)
    {
        return 0 === self::compare($this, box($another));
    }

    public function equals(System $other): bool
    {
        return $this::class === $other::class
            && $this->isEqualTo($other);
    }

    /**
     * @param System $another
     *
     * @return bool
     */
    public function isGreaterThanOrEqualTo($another)
    {
        return self::compare($this, box($another)) >= 0;
    }

    /**
     * @param System $another
     *
     * @return bool
     */
    public function isGreaterThan($another)
    {
        return self::compare($this, box($another)) > 0;
    }

    /**
     * @param System $another
     *
     * @return bool
     */
    public function isLessThanOrEqualTo($another)
    {
        return self::compare($this, box($another)) <= 0;
    }

    /**
     * @param System $another
     *
     * @return bool
     */
    public function isLessThan($another)
    {
        return self::compare($this, box($another)) < 0;
    }

    /**
     * @param System $left
     * @param System $right
     *
     * @return int
     */
    public static function compare($left, $right)
    {
        return bccomp(
            $left->numberOfBytes,
            $right->numberOfBytes,
            self::COMPUTE_WITH_PRECISION,
        );
    }

    /**
     * @param string $howToFormat
     * @param string $separator
     *
     * @return string
     */
    public function format($howToFormat = null, $separator = '')
    {
        return $this->formatter->format($this->numberOfBytes, $howToFormat, $separator);
    }

    abstract public function asBinary(): Binary;

    abstract public function asMetric(): Metric;

    /**
     * @param string $numberOfBytes
     *
     * @return int
     */
    private function normalize($numberOfBytes)
    {
        $numberOfBytes = (string) $numberOfBytes;
        if (preg_match('/^(?P<coefficient>\d+(?:\.\d+))E\+(?P<exponent>\d+)$/', $numberOfBytes, $matches)) {
            $numberOfBytes = bcmul(
                $matches['coefficient'],
                bcpow($base = 10, $matches['exponent'], self::COMPUTE_WITH_PRECISION),
            );
        }

        return $numberOfBytes;
    }

    /**
     * @throws NegativeBytesException
     */
    private function ensureIsNotNegative(int|string $numberOfBytes): string
    {
        if (bccomp($numberOfBytes, 0) < 0) {
            throw new NegativeBytesException();
        }

        return (string) $numberOfBytes;
    }

    public function numberOfBytes(): string
    {
        return $this->numberOfBytes;
    }
}
