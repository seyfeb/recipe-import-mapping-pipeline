<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use ArrayAccess;
use ArrayIterator;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;
use IteratorAggregate;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;

/**
 * Represents a JSON array.
 */
class JSONArray extends BaseJSONObject implements ArrayAccess, IteratorAggregate
{
    /** @var array<BaseJSONObject> $data The data stored in the array. */
    private array $data;

    /**
     * Constructor for the JSONArray class.
     *
     * @param array $data Optional initial data for the array.
     */
    public function __construct(array $data = []) {
        $this->data = $data;
    }

    /**
     * Add an item to the array.
     *
     * @param IJsonType $item The item to add.
     * @return void
     */
    public function add(IJsonType $item): void {
        $this->data[] = $item;
    }

    /**
     * Get the iterator for the array.
     *
     * @return ArrayIterator The iterator for the array.
     */
    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->data);
    }

    /** @inheritDoc */
    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /** @inheritDoc */
    public function offsetExists($offset): bool {
        return isset($this->data[$offset]);
    }

    /** @inheritDoc */
    public function offsetUnset($offset): void {
        unset($this->data[$offset]);
    }

    /** @inheritDoc */
    public function offsetGet($offset): mixed {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        $jsonItems = [];

        foreach ($this->data as $item) {
            if ($item instanceof IJsonType) {
                $jsonItems[] = $item->toJSON();
            }
        }

        return '[' . implode(',', $jsonItems) . ']';
    }

    /**
     * @inheritDoc
     */
    public function parseWith(IJSONObjectParser $parser) : void
    {
        $parser->handleArray($this);
    }

    /**
     * Get the complete data of the object as an iterable array.
     *
     * @return array<IJsonType> The value of the JSONObject.
     */
    public function getValue(): array
    {
        return $this->data;
    }
}