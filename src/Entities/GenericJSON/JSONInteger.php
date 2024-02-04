<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;

/**
 * Represents a JSON integer.
 */
class JSONInteger implements IBaseJsonType, IJSONSerializable
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
        // TODO: Implement toJSON() method.
    }
}