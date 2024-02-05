<?php

namespace RecipeImportPipeline\tests\Unit\Entities\GenericJSON;

use ArrayIterator;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;

class JSONArrayTest extends TestCase
{
    public function testAdd_AddingJSONString_ItemAddedToList()
    {
        // Arrange
        $array = new JSONArray();
        $jsonString = new JSONString('example');

        // Act
        $array->add($jsonString);

        // Assert
        $this->assertCount(1, $array);
        $this->assertSame($jsonString, $array[0]);
    }

    public function testGetIterator_ReturnsArrayIterator()
    {
        // Arrange
        $array = new JSONArray([new JSONString('one'), new JSONString('two'), new JSONString('three')]);

        // Act
        $iterator = $array->getIterator();

        // Assert
        $this->assertInstanceOf(ArrayIterator::class, $iterator);
        $this->assertCount(3, $iterator);
        $this->assertInstanceOf(IJsonType::class, $iterator[0]);
        $this->assertInstanceOf(IJsonType::class, $iterator[1]);
        $this->assertInstanceOf(IJsonType::class, $iterator[2]);
    }

    public function testToJSON_ReturnsValidJSON()
    {
        // Arrange
        $array = new JSONArray([
            new JSONString('one'),
            new JSONString('two'),
            new JSONString('three')
        ]);

        // Act
        $json = $array->toJSON();

        // Assert
        $this->assertEquals('["one","two","three"]', $json);
    }
}
