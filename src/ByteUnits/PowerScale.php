<?php

namespace ByteUnits;

final readonly class PowerScale
{
    public function __construct(private int $base, private array $scale, private ?int $precision)
    {
    }

    public function scaleToUnit($quantity, $unit)
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

    public function scaleFromUnit($quantity, string $unit)
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

    public function normalizeNameOfUnit($unitAsString)
    {
        foreach ($this->scale as $unit => $_) {
            if (strtolower($unit) === strtolower($unitAsString)) {
                return $unit;
            }
        }
    }

    public function normalUnitFor($quantity)
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
    }

    public function isBaseUnit(string $unit): bool
    {
        return !isset($this->scale[$unit]) || 0 === $this->scale[$unit];
    }
}
