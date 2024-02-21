<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Parsers\JSON\TimestampParser;

class TimestampParserTest extends TestCase
{
    public function testHandleString_ValidTimestamp()
    {
        // Arrange
        $parser = new TimestampParser();
        $value = new JSONString("2024-02-20T12:00:00Z");

        // Act
        $parser->handleString($value);
        $timestamp = $parser->getTimestamp();

        // Assert
        $this->assertInstanceOf(PlainText::class, $timestamp);
        $this->assertEquals("2024-02-20T12:00:00Z", $timestamp->getValue());
    }

    public function testHandleString_InvalidTimestamp()
    {
        // Arrange
        $parser = new TimestampParser();
        $value = new JSONString("invalid_timestamp");

        // Act
        $parser->handleString($value);

        // Assert
        $this->assertNull($parser->getTimestamp());
    }

    public function testHandleArray_ValidTimestamp()
    {
        // Arrange
        $parser = new TimestampParser();
        $value = new JSONArray([
            new JSONString("invalid_timestamp"),
            new JSONString("2024-02-20T12:00:00Z"),
            new JSONString("2024-02-21T10:00:00Z")
        ]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertInstanceOf(PlainText::class, $parser->getTimestamp());
        $this->assertEquals("2024-02-20T12:00:00Z", $parser->getTimestamp()->getValue());
    }

    public function testHandleArray_AllInvalidTimestamps()
    {
        // Arrange
        $parser = new TimestampParser();
        $value = new JSONArray([
            new JSONString("invalid_timestamp_1"),
            new JSONString("invalid_timestamp_2")
        ]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertNull($parser->getTimestamp());
    }

    public function testHandleArray_EmptyArray()
    {
        // Arrange
        $parser = new TimestampParser();
        $value = new JSONArray([]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertNull($parser->getTimestamp());
    }
}