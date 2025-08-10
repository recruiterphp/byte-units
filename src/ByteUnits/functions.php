<?php

namespace ByteUnits;

use Exception;

/**
 * @throws ConversionException
 * @throws Exception
 */
function box(mixed $something): System
{
    if (is_integer($something)) {
        return bytes($something);
    }
    if (is_string($something)) {
        return parse($something);
    }
    if ($something instanceof System) {
        return $something;
    }
    throw new ConversionException();
}

/**
 * @throws NegativeBytesException
 */
function bytes($numberOf): Metric
{
    return new Metric($numberOf);
}

/**
 * @throws ParseException
 * @throws \ReflectionException
 */
function parse(string $bytesAsString): System
{
    $parsers = [Metric::parser(), Binary::parser()];
    /** @var Parser $parser */
    foreach ($parsers as $parser) {
        try {
            return $parser->parse($bytesAsString);
        } catch (\ReflectionException|ParseException $e) {
            $lastParseException = $e;
        }
    }
    throw $lastParseException;
}
