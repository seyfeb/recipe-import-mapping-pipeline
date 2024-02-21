<?php

namespace RecipeImportPipeline\Entities\SchemaOrg\Utility;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;

class Supplies implements ArrayAccess, IteratorAggregate, IJsonType
{
    /** @var array<ISupply> $data The list of supplies. */
    private array $data;

    /**
     * Constructor for the Supplies class.
     *
     * @param array<ISupply> $data Optional initial data for the array.
     */
    public function __construct(array $data = []) {
        $this->data = $data;
    }

    /**
     * Add a supply item to the array.
     *
     * @param ISupply $item The item to add.
     * @return void
     */
    public function add(ISupply $item): void {
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
    public function offsetGet($offset): ?ISupply
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Get the complete data of the object as an iterable array.
     *
     * @return array<ISupply> The value of the Supplies object.
     */
    public function getValue(): array
    {
        return $this->data;
    }

}