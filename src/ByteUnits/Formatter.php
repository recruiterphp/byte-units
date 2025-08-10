<?php

namespace ByteUnits;

readonly class Formatter
{
    public function __construct(private PowerScale $converter, private int $precision)
    {
    }

    public function precision(): int
    {
        return $this->precision;
    }

    public function format(string $numberOfBytes, int|string|null $howToFormat, string $separator): string
    {
        $precision = $this->precisionFrom($howToFormat);
        $byteUnit = $this->byteUnitToFormatTo($numberOfBytes, $howToFormat);

        return $this->formatInByteUnit($numberOfBytes, $byteUnit, $precision, $separator);
    }

    private function precisionFrom(int|string|null $howToFormat): int
    {
        if (is_int($howToFormat)) {
            return $howToFormat;
        }
        if (is_string($howToFormat)) {
            if (preg_match('/^.*\/(?<precision>0*)$/', $howToFormat, $matches)) {
                return strlen($matches['precision']);
            }
            if (preg_match('/^.*\/(?<precision>\d+)$/', $howToFormat, $matches)) {
                return intval($matches['precision']);
            }
        }

        return $this->precision;
    }

    private function byteUnitToFormatTo($numberOfBytes, int|string|null $howToFormat): int|string|null
    {
        if (is_string($howToFormat)) {
            if (preg_match('/^(?P<unit>[^\/]+)(?:\/.*$)?/i', $howToFormat, $matches)) {
                if ($this->converter->isKnownUnit($matches['unit'])) {
                    return $this->converter->normalizeNameOfUnit($matches['unit']);
                }
            }
        }

        return $this->converter->normalUnitFor($numberOfBytes);
    }

    private function formatInByteUnit($numberOfBytes, $byteUnit, int $precision, string $separator): string
    {
        $scaled = $this->converter->scaleToUnit($numberOfBytes, $byteUnit);
        if (null == $byteUnit) {
            $byteUnit = 'B';
        }
        if ($this->converter->isBaseUnit($byteUnit)) {
            return sprintf('%d%s%s', $scaled, $separator, $byteUnit);
        }

        return sprintf("%.{$precision}f%s%s", $scaled, $separator, $byteUnit);
    }
}
