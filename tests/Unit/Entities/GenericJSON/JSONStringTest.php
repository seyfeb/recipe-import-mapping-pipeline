<?php

namespace RecipeImportPipeline\tests\Unit\Entities\GenericJSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use PHPUnit\Framework\TestCase;

class JSONStringTest extends TestCase
{
    public function testGetValue(): void
    {
        $value = "Hello, world!";
        $jsonString = new JSONString($value);
        $this->assertEquals($value, $jsonString->getValue());
    }

    public function testToJSON(): void
    {
        $value = "Hello, world!";
        $jsonString = new JSONString($value);
        $expectedJSON = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->assertEquals($expectedJSON, $jsonString->toJSON());
    }
}
