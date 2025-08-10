<?php

namespace ByteUnits;

class Parser
{
    private \ReflectionClass $system;

    /**
     * @param class-string $system
     * @throws \ReflectionException
     */
    public function __construct(private readonly PowerScale $scale, string $system)
    {
        $this->system = new \ReflectionClass($system);
    }

    /**
     * @param string $quantityWithUnit
     *
     * @return System
     *
     * @throws ParseException
     * @throws \ReflectionException
     */
    public function parse(string $quantityWithUnit): System
    {
        if (preg_match('/(?P<quantity>[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?)\W*(?P<unit>.*)/', $quantityWithUnit, $matches)) {
            $quantity = $matches['quantity'];
            if ($this->scale->isKnownUnit($matches['unit'])) {
                $unit = $this->scale->normalizeNameOfUnit($matches['unit']);

                return $this->system->newInstanceArgs([$this->scale->scaleFromUnit($quantity, $unit)]);
            }
            if (empty($matches['unit'])) {
                return $this->system->newInstanceArgs([$quantity]);
            }
        }
        throw new ParseException("'{$quantityWithUnit}' is not a valid byte format");
    }
}
