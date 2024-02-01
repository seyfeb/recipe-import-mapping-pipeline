<?php

namespace RecipeImportPipeline\Tests\Unit\Utilities;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Utilities\TypeUtilities;

class TypeUtilitiesTest extends TestCase
{

    public function testAs_array()
    {
        // Test with scalar value
        $value = 'test';
        $result = TypeUtilities::as_array($value);
        $this->assertEquals([$value], $result);

        // Test with array
        $value = ['test', 'array'];
        $result = TypeUtilities::as_array($value);
        $this->assertEquals($value, $result);
    }

    public function testAs_cleaned_array()
    {
        // Test with scalar value
        $value = 'test';
        $result = TypeUtilities::as_cleaned_array($value);
        $this->assertEquals([$value], $result);

        // Test with array containing null  values
        $value = ['test', null, 'array', null, 0];
        $result = TypeUtilities::as_cleaned_array($value);
        $this->assertEquals(['test', 'array', 0], $result);

        // Test with associative array containing null and undefined values
        $value = ['a' => 'test', 'b' => null, 'c' => 'array', 'd' => null, 'e' => 0];
        $result = TypeUtilities::as_cleaned_array($value);
        $this->assertEquals(['a' => 'test', 'c' => 'array', 'e' => 0], $result);

    }
}
