<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\IJSONSerializable;

/**
 * Represents a JSON array.
 */
class JSONArray implements IJSONSerializable
{
    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        // TODO: Implement toJSON() method.

        return '';
    }
}