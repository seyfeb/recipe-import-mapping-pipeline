<?php

use RecipeImportPipeline\Exceptions\ParsingException;
use RecipeImportPipeline\Interfaces\Parsers\IRawParser;

class UrlRawParser implements IRawParser
{
    /**
     * @inheritDoc
     * @throws ParsingException
     */
    public function parse(mixed $input): ?object
    {
        if(!is_string($input)){
            throw new ParsingException('Parsing failed because');
        }

        // TODO: Implement parse() method.

        return null;
    }
}