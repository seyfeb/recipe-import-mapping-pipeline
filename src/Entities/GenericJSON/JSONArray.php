<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use ArrayIterator;
use IteratorAggregate;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;
use Traversable;

/**
 * Represents a JSON array.
 */
class JSONArray implements IteratorAggregate, IJsonType, IJSONSerializable
{
    /** @var array $data The data stored in the array. */
    private $data;

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
     * @return Traversable The iterator for the array.
     */
    public function getIterator(): Traversable {
        return new ArrayIterator($this->data);
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