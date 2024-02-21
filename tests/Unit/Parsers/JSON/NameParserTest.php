<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Parsers\JSON\NameParser;

class NameParserTest extends TestCase
{
    public function testParseSingleStringName()
    {
        // Arrange
        $parser = new NameParser();
        // Create a mock JSON object representing a single name
        $singleNameJson = new JSONString('Recipe Name');

        // Act
        $singleNameJson->parseWith($parser);
        $singleName = $parser->getName();

        // Assert
        $this->assertEquals('Recipe Name', $singleName->getValue());
    }

    public function testParseArrayOfNames()
    {
        // Arrange
        $parser = new NameParser();
        // Create a mock JSON object representing an array of names
        $namesArrayJson = new JSONArray([
            new JSONString('Name 1'),
            new JSONString('Name 2'),
        ]);

        // Act
        $namesArrayJson->parseWith($parser);
        $name = $parser->getName();

        // Assert that the parsed names array is a string
        $this->assertEquals('Name 1', $name->getValue());
    }

    public function testParseArrayOfNamesWithInvalidEntries()
    {
        // Arrange
        $parser = new NameParser();
        // Create a mock JSON object representing an array of names
        $namesArrayJson = new JSONArray([
            new JSONBool(true),
            null,
            new JSONString('Name 1'),
            new JSONFloat(3.14),
            null,
            new JSONString('Name 2'),
        ]);

        // Act
        $namesArrayJson->parseWith($parser);
        $name = $parser->getName();

        // Assert that the parsed names array is a string
        $this->assertEquals('Name 1', $name->getValue());
    }

    public function testParseEmptyArrayOfNames()
    {
        // Arrange
        $parser = new NameParser();
        // Create a mock JSON object representing an empty array of names
        $emptyNamesArrayJson = new JSONArray([]);

        // Act
        $emptyNamesArrayJson->parseWith($parser);
        $emptyName = $parser->getName();

        // Assert that the parsed names array is null for an empty array
        $this->assertNull($emptyName);
    }

    public function testParseArrayWithEmptyStringName()
    {
        // Arrange
        $parser = new NameParser();
        // Create a mock JSON object representing an array with an empty string
        $emptyStringArrayJson = new JSONArray([
            new JSONString(''),
        ]);

        // Act
        $emptyStringArrayJson->parseWith($parser);
        $emptyStringArray = $parser->getName();

        // Assert that the parsed name is null for an empty string
        $this->assertNull($emptyStringArray);
    }

}