<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Recipe;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Ingredients;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Instructions;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Keywords;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Supplies;
use RecipeImportPipeline\Parsers\JSON\RecipeParser;

class RecipeParserTest extends TestCase
{
    public function testHandleObject_AllProperties()
    {
        // Arrange
        $parser = new RecipeParser();
        $jsonData = [
            "identifier" => new JSONString("123456"),
            "name" => new JSONString("Chocolate Cake"),
            "dateCreated" => new JSONString("2024-02-20T12:00:00Z"),
            "keywords" => new JSONArray([
                new JSONString("chocolate"),
                new JSONString("cake"),
                new JSONString("dessert")
            ]),
            "totalTime" => new JSONString("PT1H"),
            "recipeIngredient" => new JSONArray([
                new JSONString("Flour"),
                new JSONString("Sugar"),
                new JSONString("Eggs")
            ]),
            "recipeInstructions" => new JSONArray([
                new JSONString("Step 1: Preheat the oven"),
                new JSONString("Step 2: Mix ingredients"),
                new JSONString("Step 3: Bake")
            ]),
            "supply" => new JSONArray([
                new JSONString("Oven"),
                new JSONString("Mixing Bowl"),
                new JSONString("Whisk")
            ])
        ];
        $jsonObject = new JSONObject($jsonData);

        // Act
        $parser->handleObject($jsonObject);
        $recipe = $parser->getRecipe();

        // Assert
        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertEquals("123456", $recipe->getIdentifier()->getValue());
        $this->assertEquals("Chocolate Cake", $recipe->getName()->getValue());
        $this->assertEquals("2024-02-20T12:00:00Z", $recipe->getDateCreated()->getValue());
        $this->assertInstanceOf(Keywords::class, $recipe->getKeywords());
        $this->assertEquals(["chocolate", "cake", "dessert"], array_map(function($e) {return $e->getValue();},$recipe->getKeywords()->getValue()));
        $this->assertInstanceOf(PlainText::class, $recipe->getTotalTime());
        $this->assertEquals("PT1H", $recipe->getTotalTime()->getValue());
        $this->assertInstanceOf(Ingredients::class, $recipe->getRecipeIngredient());
        $this->assertCount(3, $recipe->getRecipeIngredient());
        $this->assertInstanceOf(Instructions::class, $recipe->getRecipeInstructions());
        $this->assertCount(3, $recipe->getRecipeInstructions());
        $this->assertInstanceOf(Supplies::class, $recipe->getSupply());
        $this->assertCount(3, $recipe->getSupply());
    }

    public function testHandleObject_MissingProperties()
    {
        // Arrange
        $parser = new RecipeParser();
        $jsonData = [
            "name" => new JSONString("Chocolate Cake"),
            "dateCreated" => new JSONString("2024-02-20T12:00:00Z"),
            "keywords" => new JSONArray([
                new JSONString("chocolate"),
                new JSONString("cake"),
                new JSONString("dessert")
            ])
            // Other properties are missing
        ];
        $jsonObject = new JSONObject($jsonData);

        // Act
        $parser->handleObject($jsonObject);
        $recipe = $parser->getRecipe();

        // Assert
        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertNull($recipe->getIdentifier());
        $this->assertEquals("Chocolate Cake", $recipe->getName()->getValue());
        $this->assertEquals("2024-02-20T12:00:00Z", $recipe->getDateCreated()->getValue());
        $this->assertInstanceOf(Keywords::class, $recipe->getKeywords());
        $this->assertEquals(["chocolate", "cake", "dessert"], array_map(function($e) {return $e->getValue();},$recipe->getKeywords()->getValue()));
        $this->assertNull($recipe->getTotalTime());
        $this->assertNull($recipe->getRecipeIngredient());
        $this->assertNull($recipe->getRecipeInstructions());
        $this->assertNull($recipe->getSupply());
    }
}
