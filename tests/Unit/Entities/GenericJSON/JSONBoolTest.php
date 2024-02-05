<?php

namespace RecipeImportPipeline\tests\Unit\Entities\GenericJSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use PHPUnit\Framework\TestCase;

class JSONBoolTest extends TestCase
{
    public function testGetValue(): void
    {
        $value = true;
        $jsonBool = new JSONBool($value);
        $this->assertEquals($value, $jsonBool->getValue());
    }

    public function testToJSON(): void
    {
        $value = true;
        $jsonBool = new JSONBool($value);
        $this->assertEquals('true', $jsonBool->toJSON());

        $value = false;
        $jsonBool = new JSONBool($value);
        $this->assertEquals('false', $jsonBool->toJSON());
    }
}
