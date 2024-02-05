<?php

namespace RecipeImportPipeline\tests\Unit\Entities\GenericJSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use PHPUnit\Framework\TestCase;

class JSONFloatTest extends TestCase
{
    public function testGetValue(): void
    {
        $value = 3.141;
        $jsonFloat = new JSONFloat($value);
        $this->assertEquals($value, $jsonFloat->getValue());
    }

    public function testToJSON(): void
    {
        $value = 3.141;
        $jsonFloat = new JSONFloat($value);
        $this->assertEquals('3.141', $jsonFloat->toJSON());

        $value = -111.12;
        $jsonFloat = new JSONFloat($value);
        $this->assertEquals('-111.12', $jsonFloat->toJSON());
    }
}
