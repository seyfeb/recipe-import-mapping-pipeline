<?php

namespace RecipeImportPipeline\Tests\Unit\Utilities;

use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;
use PHPUnit\Framework\TestCase;

class JsonMapperTest extends TestCase
{
    public function testExtractString()
    {
        // Test with string value
        $value = 'test';
        $result = JsonMapper::ExtractString($value);
        $this->assertEquals($value, $result);

        // Test with numeric value
        $value = 123;
        $result = JsonMapper::ExtractString($value);
        $this->assertEquals('123', $result);

        // Test with array containing string
        $value = ['test', 'array'];
        $result = JsonMapper::ExtractString($value);
        $this->assertEquals('test', $result);

        // Test with array containing numeric value
        $value = [456, 'array'];
        $result = JsonMapper::ExtractString($value);
        $this->assertEquals('456', $result);

        // Test with null value and allow null
        $value = null;
        $result = JsonMapper::ExtractString($value, true);
        $this->assertNull($result);

        // Test with null value and disallow null
        $this->expectException(JsonMappingException::class);
        JsonMapper::ExtractString($value, false);

        // Test with invalid value
        $value = new \stdClass();
        $this->expectException(JsonMappingException::class);
        JsonMapper::ExtractString($value);
    }

    public function testExtractStringArray()
    {
        // Test with string value
        $value = 'test';
        $result = JsonMapper::ExtractStringArray($value);
        $this->assertEquals(['test'], $result);

        // Test with numeric value
        $value = 123;
        $result = JsonMapper::ExtractStringArray($value);
        $this->assertEquals(['123'], $result);

        // Test with array containing strings
        $value = ['test', 'array'];
        $result = JsonMapper::ExtractStringArray($value);
        $this->assertEquals(['test', 'array'], $result);

        // Test with array containing numbers
        $value = [456, 789];
        $result = JsonMapper::ExtractStringArray($value);
        $this->assertEquals(['456', '789'], $result);

        // Test with array containing mixed types
        $value = ['test', 123];
        $result = JsonMapper::ExtractStringArray($value);
        $this->assertEquals(['test', '123'], $result);

        // Test with null value and allow null
        $value = null;
        $result = JsonMapper::ExtractStringArray($value, true);
        $this->assertNull($result);

        // Test with null value and disallow null
        $this->expectException(JsonMappingException::class);
        JsonMapper::ExtractStringArray($value, false);

        // Test with invalid value
        $value = new \stdClass();
        $this->expectException(JsonMappingException::class);
        JsonMapper::ExtractStringArray($value);
    }
}
