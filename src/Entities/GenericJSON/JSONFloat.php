<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

/**
 * Represents a JSON float.
 */
class JSONFloat extends BaseJSONObject
{
    /** @var float $value The value stored in the object. */
    private float $value;

    /**
     * Constructor for the JSONFloat class.
     *
     * @param float $value The float value.
     */
    public function __construct(float $value) {
        $this->value = $value;
    }

    /**
     * Get the float value.
     *
     * @return float The float value.
     */
    public function getValue(): float {
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
        $parser->handleFloat($this);
    }
}