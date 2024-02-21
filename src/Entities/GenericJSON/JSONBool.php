<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

/**
 * Represents a JSON boolean.
 */
class JSONBool extends BaseJSONObject
{
    /** @var bool $value The value stored in the object. */
    private bool $value;

    /**
     * Constructor for the JSONBool class.
     *
     * @param bool $value The boolean value.
     */
    public function __construct(bool $value) {
        $this->value = $value;
    }

    /**
     * Get the boolean value.
     *
     * @return bool The boolean value.
     */
    public function getValue(): bool {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        return $this->value ? 'true' : 'false';
    }

    /**
     * @inheritDoc
     */
    public function parseWith(IJSONObjectParser $parser) : void
    {
        $parser->handleBool($this);
    }
}