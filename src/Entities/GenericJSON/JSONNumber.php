<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\IJSONSerializable;

/**
 * Represents a JSON number.
 *
 * TODO: Consider splitting this into integer and float.
 */
class JSONNumber implements IJSONSerializable
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