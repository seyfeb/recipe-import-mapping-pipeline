<?php

namespace RecipeImportPipeline\Entities\GenericJSON;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;

/**
 * Represents a JSON object.
 */
class JSONObject implements IJsonType, IJSONSerializable {
    /** @var array $data The data stored in the object. */
    private array $data;

    /**
     * Constructor for the JSONObject class.
     *
     * @param array $data Optional initial data for the object.
     */
    public function __construct(array $data = []) {
        $this->data = $data;
    }

    /**
     * Set a property of the object.
     *
     * @param string $name The name of the property.
     * @param IJsonType|null $value The value of the property.
     * @return void
     */
    public function __set(string $name, ?IJsonType $value): void {
        $this->data[$name] = $value;
    }

    /**
     * Get a property of the object.
     *
     * @param string $name The name of the property.
     * @return IJsonType|null The value of the property, or null if the property does not exist.
     */
    public function __get(string $name): ?IJsonType {
        return $this->data[$name] ?? null;
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
