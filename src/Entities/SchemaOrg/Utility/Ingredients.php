<?php

namespace RecipeImportPipeline\Entities\SchemaOrg\Utility;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;

class Ingredients implements ArrayAccess, IteratorAggregate, IJsonType
{
    /** @var PlainText[] $data The list of ingredients. */
    private array $data;

    /**
     * Constructor for the Ingredients class.
     *
     * @param PlainText[] $data Optional initial data for the array.
     */
    public function __construct(array $data = []) {
        $this->data = $data;
    }

    /**
     * Add a keyword item to the array.
     *
     * @param string $item The item to add.
     * @return void
     */
    public function add(string $item): void {
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
    public function offsetGet($offset): ?PlainText
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Get the complete data of the object as an iterable array.
     *
     * @return PlainText[] The value of the Ingredients object.
     */
    public function getValue(): array
    {
        return $this->data;
    }

}