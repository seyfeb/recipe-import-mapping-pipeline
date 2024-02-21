<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Parsers\JSON\TimePeriodParser;

class TimePeriodParserTest extends TestCase
{
    public function testHandleString_ValidTimePeriod()
    {
        // Arrange
        $parser = new TimePeriodParser();
        $value = new JSONString("P1DT2H"); // 1 day and 2 hours

        // Act
        $parser->handleString($value);

        // Assert
        $this->assertInstanceOf(PlainText::class, $parser->getTimePeriod());
        $this->assertEquals("P1DT2H", $parser->getTimePeriod()->getValue());
    }

    public function testHandleString_InvalidTimePeriod()
    {
        // Arrange
        $parser = new TimePeriodParser();
        $value = new JSONString("invalid_time_period");

        // Act
        $parser->handleString($value);

        // Assert
        $this->assertNull($parser->getTimePeriod());
    }

    public function testHandleArray_ValidTimePeriod()
    {
        // Arrange
        $parser = new TimePeriodParser();
        $value = new JSONArray([
            new JSONString("invalid_time_period"),
            new JSONString("PT4H"), // 4 hours
            new JSONString("P2D")   // 2 days
        ]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertInstanceOf(PlainText::class, $parser->getTimePeriod());
        $this->assertEquals("PT4H", $parser->getTimePeriod()->getValue());
    }

    public function testHandleArray_AllInvalidTimePeriods()
    {
        // Arrange
        $parser = new TimePeriodParser();
        $value = new JSONArray([
            new JSONString("invalid_time_period_1"),
            new JSONString("invalid_time_period_2")
        ]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertNull($parser->getTimePeriod());
    }

    public function testHandleArray_EmptyArray()
    {
        // Arrange
        $parser = new TimePeriodParser();
        $value = new JSONArray([]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertNull($parser->getTimePeriod());
    }
}