<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Ingredients;
use RecipeImportPipeline\Parsers\JSON\IngredientsParser;

class IngredientsParserTest extends TestCase
{
    public function testHandleString_SingleIngredient()
    {
        // Arrange
        $parser = new IngredientsParser();
        $value = new JSONString("Flour");

        // Act
        $parser->handleString($value);
        $ingredients = $parser->getIngredients();

        // Assert
        $this->assertInstanceOf(Ingredients::class, $ingredients);
        $this->assertCount(1, $ingredients);
        $this->assertEquals("Flour", $ingredients[0]->getValue());
    }

    public function testHandleArray_MultipleIngredients()
    {
        // Arrange
        $parser = new IngredientsParser();
        $value = new JSONArray([
            new JSONString("Flour"),
            new JSONString("Sugar"),
            new JSONString("Eggs")
        ]);

        // Act
        $parser->handleArray($value);
        $ingredients = $parser->getIngredients();

        // Assert
        $this->assertInstanceOf(Ingredients::class, $ingredients);
        $this->assertCount(3, $ingredients);
        $this->assertEquals("Flour", $ingredients[0]->getValue());
        $this->assertEquals("Sugar", $ingredients[1]->getValue());
        $this->assertEquals("Eggs", $ingredients[2]->getValue());
    }

    public function testHandleArray_WithNonStringValues()
    {
        // Arrange
        $parser = new IngredientsParser();
        $value = new JSONArray([
            new JSONString("Flour"),
            new JSONInteger(100), // This should be ignored
            new JSONBool(true),   // This should be ignored
            new JSONString("Sugar")
        ]);

        // Act
        $parser->handleArray($value);
        $ingredients = $parser->getIngredients();

        // Assert
        $this->assertInstanceOf(Ingredients::class, $ingredients);
        $this->assertCount(2, $ingredients);
        $this->assertEquals("Flour", $ingredients[0]->getValue());
        $this->assertEquals("Sugar", $ingredients[1]->getValue());
    }

    public function testHandleArray_EmptyArray()
    {
        // Arrange
        $parser = new IngredientsParser();
        $value = new JSONArray([]);

        // Act
        $parser->handleArray($value);

        // Assert
        $this->assertNull($parser->getIngredients());
    }
}