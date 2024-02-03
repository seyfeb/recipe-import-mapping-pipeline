<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\IJSONSerializable;

/**
 * Represents a JSON boolean.
 */
class JSONBool implements IJSONSerializable
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