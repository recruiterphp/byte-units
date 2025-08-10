<?php

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

function bytes(int|string $numberOf): Metric
{
    return new Metric($numberOf);
}

/**
 * @throws ParseException
 */
function parse(string $bytesAsString): System
{
    $lastParseException = null;
    $parsers = [Metric::parser(), Binary::parser()];
    foreach ($parsers as $parser) {
        try {
            return $parser->parse($bytesAsString);
        } catch (ParseException $lastParseException) {
        } catch (\Exception $e) {
            $lastParseException = new ParseException($e->getMessage(), $e->getCode(), $e);
        }
    }
    throw $lastParseException;
}
