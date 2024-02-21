<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextSupply;
use RecipeImportPipeline\Parsers\JSON\SupplyParser;

class SupplyParserTest extends TestCase
{
    public function testParsingSupplyFromString()
    {
        // Arrange
        $parser = new SupplyParser();
        // Create a mock JSON object representing a single supply
        $singleSupplyJson = new JSONString('Ingredient 1');

        // Act
        $singleSupplyJson->parseWith($parser);
        $singleSupply = $parser->getSupply();

        // Assert
        $this->assertInstanceOf(PlainTextSupply::class, $singleSupply);
        $this->assertEquals('Ingredient 1', $singleSupply->getValue());
    }

    public function testParsingSupplyFromHowToSupply()
    {
        // Arrange
        $parser = new SupplyParser();
        // Create a mock JSON object representing a HowToSupply
        $howToSupplyJson = new JSONObject([
            '@type' => new JSONString('HowToSupply'),
            'Name' => new JSONString('Ingredient 2'),
            // Add more properties as needed
        ]);

        // Act
        $howToSupplyJson->parseWith($parser);
        $howToSupply = $parser->getSupply();

        // Assert
        $this->assertInstanceOf(HowToSupply::class, $howToSupply);
        $this->assertEquals('Ingredient 2', $howToSupply->getName());
    }
}