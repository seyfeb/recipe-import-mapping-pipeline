<?php
namespace RecipeImportPipeline\Tests\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextSupply;
use RecipeImportPipeline\Parsers\JSON\HowToDirectionParser;

class HowToDirectionParserTest extends TestCase
{
    public function testParsing()
    {
        // Arrange
        $parser = new HowToDirectionParser();
        // Create a mock JSON object representing a HowToDirection
        $json = new JSONObject([
            '@type' => new JSONString('HowToDirection'),
            'Text' => new JSONString('Cook the pasta'),
            'Supply' => new JSONArray([
                new JSONString('Pasta'),
                new JSONString('Water'),
                // Add more supplies as needed
            ]),
        ]);

        // Act
        $json->parseWith($parser);
        $direction = $parser->getDirection();

        // Assert
        $this->assertInstanceOf(HowToDirection::class, $direction);

        $this->assertEquals('Cook the pasta', $direction->getText()[0]->getValue());
        $this->assertCount(2, $direction->getSupply());
        $this->assertInstanceOf(PlainTextSupply::class, $direction->getSupply()[0]);
        $this->assertEquals('Pasta', $direction->getSupply()[0]->getValue());
    }
    public function testParsingWithTextArray()
    {
        // Arrange
        $parser = new HowToDirectionParser();
        // Create a mock JSON object representing a HowToDirection
        $json = new JSONObject([
            '@type' => new JSONString('HowToDirection'),
            'Text' =>
                new JSONArray([
                    new JSONString('Cook the pasta'),
                    new JSONString('Cook the pasta again'),
                ]),
        ]);

        // Act
        $json->parseWith($parser);
        $direction = $parser->getDirection();

        // Assert
        $this->assertInstanceOf(HowToDirection::class, $direction);

        $this->assertCount(2, $direction->getText());
        $this->assertEquals('Cook the pasta', $direction->getText()[0]->getValue());
        $this->assertEquals('Cook the pasta again', $direction->getText()[1]->getValue());
        $this->assertNull($direction->getSupply());
    }
}