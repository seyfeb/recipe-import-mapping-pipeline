<?php

namespace RecipeImportPipeline\Interfaces\Parsers;
/**
 * Interface for parsers.
 */
interface IParser
{
    /**
     * Parse the input and return the result.
     *
     * @param mixed $input The input to parse.
     * @return mixed The parsed output.
     */
    public function parse(mixed $input): mixed;
}
