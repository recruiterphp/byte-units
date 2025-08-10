<?php

namespace ByteUnits;

final readonly class PowerScale
{
    /**
     * @param array<string, int> $scale
     */
    public function __construct(private int $base, private array $scale, private int $precision)
    {
    }

    public function scaleToUnit(string $quantity, ?string $unit): string
    {
        if ('0' === $quantity) {
            return '0';
        }

        return bcdiv(
            $quantity,
            bcpow($this->base, $this->scale[$unit], $this->precision),
            $this->precision,
        );
    }

    public function scaleFromUnit(int|float|string $quantity, string $unit): float|int
    {
        return $quantity * bcpow(
            $this->base,
            $this->scale[$unit],
            $this->precision,
        );
    }

    public function isKnownUnit(string $unitAsString): bool
    {
        return preg_match(
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

    public function normalUnitFor($quantity): int|string|null
    {
        if (0 === $quantity) {
            return 'B';
        }
        foreach ($this->scale as $unit => $_) {
            $scaled = $this->scaleToUnit($quantity, $unit);
            if (bccomp($scaled, 1) >= 0) {
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
