<?php

declare(strict_types=1);

namespace ByteUnits;

function box(int|string|System $something): System
{
    if (is_int($something)) {
        return bytes($something);
    }
    if (is_string($something)) {
        return parse($something);
    }

    return $something;
}

function bytes(int|float|string $numberOf): Metric
{
    return new Metric($numberOf);
}

function parse(string $bytesAsString): System
{
    $parsers = [Metric::parser(), Binary::parser()];
    foreach ($parsers as $parser) {
        try {
            return $parser->parse($bytesAsString);
        } catch (ParseException $e) {
            $lastParseException = $e;
        }
    }

    /** @var ParseException $lastParseException */
    throw $lastParseException;
}
