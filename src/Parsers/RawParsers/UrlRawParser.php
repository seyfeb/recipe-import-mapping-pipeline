<?php

namespace RecipeImportPipeline\Parsers\RawParsers;

use InvalidArgumentException;
use RecipeImportPipeline\Exceptions\ParsingException;
use RecipeImportPipeline\Interfaces\Parsers\IParser;
use RecipeImportPipeline\Interfaces\Parsers\IRawParser;

class UrlRawParser implements IParser, IRawParser
{
    /**
     * @inheritDoc
     * @param mixed $input
     * @return string|null A JSON string
     * @throws InvalidArgumentException Thrown when function parameter is not a valid URL.
     */
    public function parse(mixed $input): ?object
    {
        if (filter_var($input, FILTER_VALIDATE_URL) === FALSE) {
            throw new InvalidArgumentException('Function parameter is not a URL.');
        }

        // TODO: Implement parse() method.

        if(false){
            throw new ParsingException('Parsing failed because');
        }

        return null;
    }
}