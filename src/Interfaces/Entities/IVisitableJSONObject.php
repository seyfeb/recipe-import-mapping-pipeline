<?php

namespace RecipeImportPipeline\Interfaces\Entities;

use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

// Visitor pattern
/**
 * Interface for classes that can be visited by an object parser.
 */
interface IVisitableJSONObject {
    /**
     * Accepts a visitor/parser and lets it parse this object.
     *
     * @param IJSONObjectParser $parser The parser to be accepted.
     * @return void
     */
    public function parseWith(IJSONObjectParser $parser) : void;
}
