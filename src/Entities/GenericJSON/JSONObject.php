<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;
use RecipeImportPipeline\Interfaces\Entities\IVisitableJSONObject;

/**
 * Represents a JSON object.
 */
class JSONObject implements IJsonType, IJSONSerializable, IVisitableJSONObject {
    /** @var array<IJsonType> $data The data stored in the object. */
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
        $json = [];
        foreach ($this->data as $key => $value) {
            if ($value instanceof IJSONSerializable) {
                $json[$key] = json_decode($value->toJSON());
            } elseif ($value instanceof IJsonType) {
                $json[$key] = $value->getValue();
            }
        }
        return json_encode($json);
    }

    /**
     * @inheritDoc
     */
    public function parseWith(IJSONObjectParser $parser) : void
    {
        $parser->handleObject($this);
    }
}
