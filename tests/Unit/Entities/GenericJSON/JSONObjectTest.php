<?php

namespace RecipeImportPipeline\tests\Unit\Entities\GenericJSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;

class JSONObjectTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $jsonObject = new JSONObject();
        $jsonObject->name = new JSONString('John');
        $jsonObject->age = new JSONInteger(30);

        $this->assertInstanceOf(JSONString::class, $jsonObject->name);
        $this->assertInstanceOf(JSONInteger::class, $jsonObject->age);
        $this->assertEquals('John', $jsonObject->name->getValue());
        $this->assertEquals(30, $jsonObject->age->getValue());
    }

    public function testToJSON(): void
    {
        $jsonObject = new JSONObject();
        $jsonObject->name = new JSONString('John');
        $jsonObject->age = new JSONInteger(30);

        $expectedJson = '{"name":"John","age":30}';
        $this->assertEquals($expectedJson, $jsonObject->toJSON());
    }

    public function testAllJSONDataTypesWithNesting(): void
    {
        $jsonObject = new JSONObject();
        $jsonObject->string = new JSONString('Hello');
        $jsonObject->integer = new JSONInteger(123);
        $jsonObject->float = new JSONFloat(3.14);
        $jsonObject->bool = new JSONBool(true);
        $jsonObject->array = new JSONArray([
            new JSONString('apple'),
            new JSONString('banana'),
            new JSONString('orange')
        ]);

        $nestedJsonObject = new JSONObject();
        $nestedJsonObject->nestedString = new JSONString('Nested String');
        $nestedJsonObject->nestedInt = new JSONInteger(456);

        $jsonObject->nestedObject = $nestedJsonObject;

        $nestedJsonArray = new JSONArray([
            new JSONString('nested1'),
            new JSONString('nested2'),
            new JSONString('nested3')
        ]);

        $jsonObject->nestedArray = $nestedJsonArray;

        $expectedJson = '{"string":"Hello","integer":123,"float":3.14,"bool":true,"array":["apple","banana","orange"],"nestedObject":{"nestedString":"Nested String","nestedInt":456},"nestedArray":["nested1","nested2","nested3"]}';

        $this->assertEquals($expectedJson, $jsonObject->toJSON());
    }
}
