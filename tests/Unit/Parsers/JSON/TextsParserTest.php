<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Parsers\JSON\TextsParser;

class TextsParserTest extends TestCase
{
    public function testParsingSingleString()
    {
        // Arrange
        $parser = new TextsParser();

        // Create a mock JSON object representing a single text
        $singleTextJson = new JSONString('Chop the vegetables');

        // Act
        $singleTextJson->parseWith($parser);
        $singleText = $parser->getTexts();

        // Assert
        $this->assertIsArray($singleText);
        $this->assertCount(1, $singleText);
        $this->assertEquals('Chop the vegetables', $singleText[0]->getValue());
    }
    public function testParsingStringArray()
    {
        // Arrange
        $parser = new TextsParser();
        // Create a mock JSON array containing text elements
        $textArrayJson = new JSONArray([
            new JSONString('Chop the vegetables'),
            new JSONString('Preheat the oven'),
            // Add more text elements as needed
        ]);

        // Act
        $textArrayJson->parseWith($parser);
        $textArray = $parser->getTexts();

        // Assert
        $this->assertIsArray($textArray);
        $this->assertCount(2, $textArray);
        $this->assertEquals('Chop the vegetables', $textArray[0]->getValue());
        $this->assertEquals('Preheat the oven', $textArray[1]->getValue());
    }
}