<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use ArrayAccess;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;

/**
 * Represents a JSON object.
 */
class JSONObject extends BaseJSONObject implements ArrayAccess
{
    /** @var array<BaseJSONObject> $data The data stored in the object. */
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
     * @param BaseJSONObject|null $value The value of the property.
     * @return void
     */
    public function __set(string $name, ?BaseJSONObject $value): void {
        $this->data[$name] = $value;
    }

    /**
     * Get a property of the object.
     *
     * @param string $name The name of the property.
     * @return BaseJSONObject|null The value of the property, or null if the property does not exist.
     */
    public function __get(string $name): ?BaseJSONObject {
        return $this->data[$name] ?? null;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset) : mixed
    {
        return $this->data[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * Get the complete data of the object as an iterable array.
     *
     * @return array<BaseJSONObject> The data of the JSONObject.
     */
    public function getValue(): array
    {
        return $this->data;
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
