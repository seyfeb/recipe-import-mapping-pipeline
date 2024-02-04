<?php

namespace RecipeImportPipeline\tests\Unit\Entities\SchemaOrg;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSection;
use RecipeImportPipeline\Entities\SchemaOrg\HowToStep;
use RecipeImportPipeline\Entities\SchemaOrg\Recipe;

class RecipeTest extends TestCase
{
    public function testFromJson_WithValidJson_ReturnsRecipeObject()
    {
        // Arrange
        $json = [
            'identifier' => '12345',
            'name' => 'Test Recipe',
            'recipeIngredient' => ['Ingredient 1', 'Ingredient 2'],
            'recipeInstructions' => [
                ['@type' => 'HowToStep', 'text' => 'Step 1'],
                ['@type' => 'HowToSection', 'name' => 'Section 1', 'itemListElement' => ['Step 2', 'Step 3']]
            ]
        ];

        // Act
        $recipe = Recipe::fromJson($json);

        // Assert
        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertEquals('12345', $recipe->getId());
        $this->assertEquals('Test Recipe', $recipe->Name);
        $this->assertCount(2, $recipe->Supply);
        $this->assertInstanceOf(HowToStep::class, $recipe->RecipeInstructions[0]);
        $this->assertInstanceOf(HowToSection::class, $recipe->RecipeInstructions[1]);
        $this->assertCount(2, $recipe->RecipeInstructions[1]->ItemListElement);
    }

    public function testFromJson_WithMissingRequiredField()
    {
        // Arrange
        $json = [
            // required 'name' property is missing
            'identifier' => '123',
            'recipeIngredient' => ['Ingredient 1', 'Ingredient 2'],
            'recipeInstructions' => [
                ['@type' => 'HowToStep', 'text' => 'Step 1'],
                ['@type' => 'HowToSection', 'name' => 'Section 1', 'itemListElement' => ['Step 2', 'Step 3']]
            ]
        ];

        // Act
        $recipe = Recipe::fromJson($json);

        // Assert
        $this->assertNull($recipe);
    }
}
