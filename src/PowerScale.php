<?php

declare(strict_types=1);

namespace ByteUnits;

final readonly class PowerScale
{
    /**
     * @param numeric-string     $base
     * @param array<string, int> $scale
     */
    public function __construct(private string $base, private array $scale, private int $precision)
    {
    }

    /**
     * @param numeric-string $quantity
     *
     * @return numeric-string
     */
    public function scaleToUnit(string $quantity, ?string $unit): string
    {
        if ('0' === $quantity) {
            return '0';
        }

        return bcdiv(
            $quantity,
            bcpow($this->base, (string) $this->scale[$unit], $this->precision),
            $this->precision,
        );
    }

    /**
     * @param int|float|numeric-string $quantity
     *
     * @return numeric-string
     */
    public function scaleFromUnit(int|float|string $quantity, string $unit): string
    {
        return bcmul(
            (string) $quantity,
            bcpow(
                $this->base,
                (string) $this->scale[$unit],
                $this->precision,
            ),
            0,
        );
    }

    public function isKnownUnit(string $unitAsString): bool
    {
        return (bool) preg_match(
            '/^(?:' . implode('|', array_keys($this->scale)) . ')$/i',
            trim($unitAsString),
        );
    }

    public function normalizeNameOfUnit(string $unitAsString): string
    {
        foreach ($this->scale as $unit => $_) {
            if (strtolower($unit) === strtolower($unitAsString)) {
                return $unit;
            }
        }

        return $unitAsString;
    }

    /**
     * @param numeric-string $quantity
     */
    public function normalUnitFor(string $quantity): ?string
    {
        if ('0' === $quantity) {
            return 'B';
        }

        foreach ($this->scale as $unit => $_) {
            $scaled = $this->scaleToUnit($quantity, $unit);
            if (bccomp($scaled, '1') >= 0) {
                return $unit;
            }
        }

        return null;
    }

    public function isBaseUnit(string $unit): bool
    {
        return !isset($this->scale[$unit]) || 0 === $this->scale[$unit];
    }
}
