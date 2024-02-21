<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Supplies;
use RecipeImportPipeline\Parsers\JSON\SuppliesParser;

class SuppliesParserTest extends TestCase
{
    public function testParseSingleStringSupply()
    {
        // Arrange
        $parser = new SuppliesParser();
        // Create a mock JSON object representing a single supply
        $singleSupplyJson = new JSONString('Ingredient 1');

        // Act
        $singleSupplyJson->parseWith($parser);
        $supplies = $parser->getSupplies();

        // Assert
        $this->assertInstanceOf(Supplies::class, $supplies);
        $this->assertCount(1, $supplies);
        $this->assertEquals('Ingredient 1', $supplies[0]->getValue());
    }

    public function testParseSingleHowToSupply()
    {
        // Arrange
        $parser = new SuppliesParser();
        // Create a mock JSON object representing a HowToSupply
        $howToSupplyJson = new JSONObject([
            '@type' => new JSONString('HowToSupply'),
            'Name' => new JSONString('Ingredient 4'),
            // Add more properties as needed
        ]);

        // Act
        $howToSupplyJson->parseWith($parser);
        $supplies = $parser->getSupplies();

        // Assert
        $this->assertInstanceOf(Supplies::class, $supplies);
        $this->assertCount(1, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertEquals('Ingredient 4', $supplies[0]->getName());
        // Add more assertions for other properties as needed
    }

    public function testParseArrayOfSupplies()
    {
        // Arrange
        $parser = new SuppliesParser();
        // Create a mock JSON object representing an array of supplies
        $howToSupplyJson = new JSONObject([
            '@type' => new JSONString('HowToSupply'),
            'Name' => new JSONString('Ingredient 2'),
            // Add more properties as needed
        ]);
        $suppliesArrayJson = new JSONArray([
            new JSONString('Ingredient 1'),
            $howToSupplyJson,
        ]);

        // Act
        $suppliesArrayJson->parseWith($parser);
        $supplies = $parser->getSupplies();

        // Assert
        $this->assertInstanceOf(Supplies::class, $supplies);
        $this->assertCount(2, $supplies);
        $this->assertEquals('Ingredient 1', $supplies[0]->getValue());
        $this->assertEquals('Ingredient 2', $supplies[1]->getName());
    }
}