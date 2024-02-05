<?php

namespace RecipeImportPipeline\tests\Unit\Entities\GenericJSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use PHPUnit\Framework\TestCase;

class JSONIntegerTest extends TestCase
{
    public function testGetValue(): void
    {
        $value = 42;
        $jsonInteger = new JSONInteger($value);
        $this->assertEquals($value, $jsonInteger->getValue());
    }

    public function testToJSON(): void
    {
        $value = 42;
        $jsonInteger = new JSONInteger($value);
        $this->assertEquals('42', $jsonInteger->toJSON());

        $value = -1000;
        $jsonInteger = new JSONInteger($value);
        $this->assertEquals('-1000', $jsonInteger->toJSON());
    }
}
