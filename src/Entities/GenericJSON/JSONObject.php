<?php

namespace RecipeImportPipeline\Entities\GenericJSON;
use RecipeImportPipeline\Interfaces\IJSONSerializable;

/**
 * Represents a JSON object.
 */
class JSONObject implements IJSONSerializable {

    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        // TODO: Implement toJSON() method.

        return '';
    }
}
