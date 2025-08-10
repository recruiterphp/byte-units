<?php

namespace ByteUnits;

abstract class System
{
    protected const int DEFAULT_FORMAT_PRECISION = 2;
    protected const int COMPUTE_WITH_PRECISION = 10;

    protected $formatter;
    protected string|int $numberOfBytes;

    public static function bytes(int|string $numberOf, int $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION): static
    {
        return new static($numberOf, $formatWithPrecision);
    }

    public static function parse(string $bytesAsString): static
    {
        return static::parser()->parse($bytesAsString);
    }

    /**
     * @throws NegativeBytesException
     */
    public function __construct($numberOfBytes, $formatter)
    {
        $this->numberOfBytes = $this->ensureIsNotNegative($this->normalize($numberOfBytes));
        $this->formatter = $formatter;
    }

    /**
     * @throws ConversionException
     * @throws NegativeBytesException
     * @throws \Exception
     */
    public function add(mixed $another): static
    {
        return new static(
            bcadd($this->numberOfBytes, box($another)->numberOfBytes, self::COMPUTE_WITH_PRECISION),
            $this->formatter->precision()
        );
    }

    /**
     * @throws ConversionException
     * @throws NegativeBytesException
     * @throws \Exception
     */
    public function remove(mixed $another): static
    {
        return new static(
            bcsub($this->numberOfBytes, box($another)->numberOfBytes, self::COMPUTE_WITH_PRECISION),
            $this->formatter->precision()
        );
    }

    /**
     * @throws ConversionException
     * @throws \Exception
     */
    public function equals(System $another): bool
    {
        if ($this::class !== $another::class) {
            return false;
        }

        return self::compare($this, $another) === 0;
    }

    /**
     * @throws \Exception
     */
    public function isEqualTo(mixed $another): bool
    {
        return self::compare($this, box($another)) === 0;
    }

    /**
     * @param System $another
     * @return bool
     * @throws ConversionException
     */
    public function isGreaterThanOrEqualTo(mixed $another): bool
    {
        return self::compare($this, box($another)) >= 0;
    }

    /**
     * @param System $another
     * @return bool
     */
    public function isGreaterThan(mixed $another)
    {
        return self::compare($this, box($another)) > 0;
    }

    /**
     * @param System $another
     * @return bool
     */
    public function isLessThanOrEqualTo($another)
    {
        return self::compare($this, box($another)) <= 0;
    }

    /**
     * @throws \Exception
     */
    public function isLessThan(mixed $another): bool
    {
        return self::compare($this, box($another)) < 0;
    }

    public static function compare(System $left, System $right): int
    {
        return bccomp(
            $left->numberOfBytes,
            $right->numberOfBytes,
            self::COMPUTE_WITH_PRECISION
        );
    }

    /**
     * @param string $howToFormat
     * @param string $separator
     * @return string
     */
    public function format($howToFormat = null, $separator = '')
    {
        return $this->formatter->format($this->numberOfBytes, $howToFormat, $separator);
    }

    public function asBinary(): Binary
    {
        return Binary::bytes($this->numberOfBytes);
    }

    /**
     * @return System
     */
    public function asMetric()
    {
        return Metric::bytes($this->numberOfBytes);
    }

    private function normalize(string $numberOfBytes): string
    {
        $numberOfBytes = (string) $numberOfBytes;
        if (preg_match('/^(?P<coefficient>\d+(?:\.\d+))E\+(?P<exponent>\d+)$/', $numberOfBytes, $matches)) {
            $numberOfBytes = bcmul(
                $matches['coefficient'],
                bcpow($base = 10, $matches['exponent'], self::COMPUTE_WITH_PRECISION)
            );
        }
        return $numberOfBytes;
    }

    /**
     * @throws NegativeBytesException
     */
    private function ensureIsNotNegative(int|string $numberOfBytes): int|string
    {
        if (bccomp($numberOfBytes, 0) < 0) {
            throw new NegativeBytesException();
        }
        return $numberOfBytes;
    }

    public function numberOfBytes(): int|string
    {
        return $this->numberOfBytes;
    }
}
