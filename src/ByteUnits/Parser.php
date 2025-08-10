<?php

declare(strict_types=1);

namespace ByteUnits;

/**
 * @template T of System
 */
readonly class Parser
{
    /**
     * @param \Closure(int|float|string): T $closure
     */
    public function __construct(private PowerScale $scale, private \Closure $closure)
    {
    }

    /**
     * @phpstan-return T
     *
     * @throws ParseException
     */
    public function parse(string $quantityWithUnit): System
    {
        if (preg_match('/(?P<quantity>[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?)\W*(?P<unit>.*)/', $quantityWithUnit, $matches)) {
            $quantity = $matches['quantity'];
            if ($this->scale->isKnownUnit($matches['unit'])) {
                $unit = $this->scale->normalizeNameOfUnit($matches['unit']);

                return ($this->closure)($this->scale->scaleFromUnit($quantity, $unit));
            }
            if (empty($matches['unit'])) {
                return ($this->closure)($quantity);
            }
        }
        throw new ParseException("'{$quantityWithUnit}' is not a valid byte format");
    }
}
