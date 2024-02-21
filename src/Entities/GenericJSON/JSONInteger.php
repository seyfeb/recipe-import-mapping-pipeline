<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

/**
 * Represents a JSON integer.
 */
class JSONInteger extends BaseJSONObject
{
    /** @var int $value The value stored in the object. */
    private $value;

    /**
     * Constructor for the JSONInteger class.
     *
     * @param int $value The integer value.
     */
    public function __construct(int $value) {
        $this->value = $value;
    }

    /**
     * Get the integer value.
     *
     * @return int The integer value.
     */
    public function getValue(): int {
        return $this->value;
    }
    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        return strval($this->value);
    }

    /**
     * @inheritDoc
     */
    public function parseWith(IJSONObjectParser $parser) : void
    {
        $parser->handleInt($this);
    }
}