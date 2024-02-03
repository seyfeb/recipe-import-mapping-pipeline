<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;

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