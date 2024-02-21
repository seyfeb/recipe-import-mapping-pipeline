<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Keywords;
use RecipeImportPipeline\Parsers\JSON\KeywordsParser;

class KeywordsParserTest extends TestCase
{
    public function testParseSingleStringKeyword()
    {
        // Arrange
        $parser = new KeywordsParser();
        // Create a mock JSON object representing a single keyword
        $singleKeywordJson = new JSONString('keyword');

        // Act
        // Create an instance of the KeywordsParser
        $singleKeywordJson->parseWith($parser);
        $keywords = $parser->getKeywords();

        // Assert
        $this->assertInstanceOf(Keywords::class, $keywords);
        $this->assertCount(1, $keywords);
        $this->assertEquals('keyword', $keywords[0]->getValue());
    }

    public function testParseArrayOfKeywords()
    {
        // Arrange
        $parser = new KeywordsParser();
        // Create a mock JSON object representing an array of keywords
        $keywordsArrayJson = new JSONArray([
            new JSONString('keyword1'),
            new JSONString('keyword2'),
        ]);

        // Act
        $keywordsArrayJson->parseWith($parser);
        $keywords = $parser->getKeywords();

        // Assert
        $this->assertInstanceOf(Keywords::class, $keywords);
        $this->assertCount(2, $keywords);
        $this->assertEquals('keyword1', $keywords[0]->getValue());
        $this->assertEquals('keyword2', $keywords[1]->getValue());
    }

    public function testParseArrayWithNonStringItems()
    {
        // Arrange
        $parser = new KeywordsParser();
        // Create a mock JSON object representing an array with non-string items
        $nonStringArrayJson = new JSONArray([
            new JSONString('keyword'),
            new JSONInteger(123), // Non-string item
            new JSONBool(true), // Non-string item
            null
        ]);

        // Act
        $nonStringArrayJson->parseWith($parser);
        $keywords = $parser->getKeywords();

        // Assert
        $this->assertInstanceOf(Keywords::class, $keywords);
        $this->assertCount(1, $keywords);
        $this->assertEquals('keyword', $keywords[0]->getValue());
    }

    public function testParseEmptyArray()
    {
        // Arrange
        $parser = new KeywordsParser();
        $emptyArrayJson = new JSONArray([]);

        // Act
        $emptyArrayJson->parseWith($parser);
        $parsedKeywords = $parser->getKeywords();

        // Assert
        $this->assertNull($parsedKeywords);
    }
}