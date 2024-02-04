<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;

/**
 * Represents a JSON string.
 */
class JSONString implements IBaseJsonType, IJSONSerializable
{
    /** @var string $value The value stored in the object. */
    private string $value;

    /**
     * Constructor for the JSONString class.
     *
     * @param string $value The string value.
     */
    public function __construct(string $value) {
        $this->value = $value;
    }

    /**
     * Get the string value.
     *
     * @return string The string value.
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        // TODO: Implement toJSON() method.

        return '';
    }
}