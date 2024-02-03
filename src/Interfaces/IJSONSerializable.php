<?php

namespace RecipeImportPipeline\Interfaces;

/**
 * Interface for classes that can be serialized to JSON.
 */
interface IJSONSerializable {
    /**
     * Convert the object to a JSON string.
     *
     * @return string The JSON representation of the object.
     */
    public function toJSON(): string;
}