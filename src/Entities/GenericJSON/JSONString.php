<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;
use RecipeImportPipeline\Interfaces\Entities\IVisitableJSONObject;

/**
 * Represents a JSON string.
 */
class JSONString implements IJsonType, IJSONSerializable, IVisitableJSONObject
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
        return json_encode($this->value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @inheritDoc
     */
    public function parseWith(IJSONObjectParser $parser) : void
    {
        $parser->handleString($this);
    }
}