<?php

declare(strict_types=1);

namespace ByteUnits;

abstract class System
{
    public const int DEFAULT_FORMAT_PRECISION = 2;
    public const int COMPUTE_WITH_PRECISION = 10;
    protected string $numberOfBytes;

    /**
     * @return Parser<static>
     */
    abstract protected static function parser(): Parser;

    abstract public static function bytes(int|float|string $numberOf, int $formatWithPrecision = self::DEFAULT_FORMAT_PRECISION): static;

    public static function parse(string $bytesAsString): static
    {
        return static::parser()->parse($bytesAsString);
    }

    final protected function __construct(int|float|string $numberOfBytes, protected Formatter $formatter)
    {
        $this->numberOfBytes = $this->ensureIsNotNegative($this->normalize($numberOfBytes));
    }

    public function add(int|string|System $another): static
    {
        return new static(
            bcadd($this->numberOfBytes, box($another)->numberOfBytes, self::COMPUTE_WITH_PRECISION),
            $this->formatter,
        );
    }

    public function remove(int|string|System $another): static
    {
        return new static(
            bcsub($this->numberOfBytes, box($another)->numberOfBytes, self::COMPUTE_WITH_PRECISION),
            $this->formatter,
        );
    }

    public function isEqualTo(int|string|System $another): bool
    {
        return 0 === self::compare($this, box($another));
    }

    public function equals(System $other): bool
    {
        return static::class === $other::class
            && $this->isEqualTo($other);
    }

    public function isGreaterThanOrEqualTo(int|string|System $another): bool
    {
        return self::compare($this, box($another)) >= 0;
    }

    public function isGreaterThan(int|string|System $another): bool
    {
        return self::compare($this, box($another)) > 0;
    }

    public function isLessThanOrEqualTo(int|string|System $another): bool
    {
        return self::compare($this, box($another)) <= 0;
    }

    public function isLessThan(int|string|System $another): bool
    {
        return self::compare($this, box($another)) < 0;
    }

    public static function compare(System $left, System $right): int
    {
        return bccomp(
            $left->numberOfBytes,
            $right->numberOfBytes,
            self::COMPUTE_WITH_PRECISION,
        );
    }

    public function format(int|string|null $howToFormat = null, string $separator = ''): string
    {
        return $this->formatter->format($this->numberOfBytes, $howToFormat, $separator);
    }

    abstract public function asBinary(): Binary;

    abstract public function asMetric(): Metric;

    private function normalize(string|int|float $numberOfBytes): string
    {
        $numberOfBytes = (string) $numberOfBytes;
        if (preg_match('/^(?P<coefficient>\d+(?:\.\d+))E\+(?P<exponent>\d+)$/', $numberOfBytes, $matches)) {
            $numberOfBytes = bcmul(
                $matches['coefficient'],
                bcpow($base = '10', $matches['exponent'], self::COMPUTE_WITH_PRECISION),
            );
        }

        return $numberOfBytes;
    }

    /**
     * @throws NegativeBytesException
     */
    private function ensureIsNotNegative(string $numberOfBytes): string
    {
        if (bccomp($numberOfBytes, '0') < 0) {
            throw new NegativeBytesException();
        }

        return $numberOfBytes;
    }

    public function numberOfBytes(): string
    {
        return $this->numberOfBytes;
    }
}
