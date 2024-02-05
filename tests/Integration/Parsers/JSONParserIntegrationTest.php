<?php

namespace RecipeImportPipeline\Tests\Integration\Parsers;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Parsers\Import\FlattenJSONParser;
use RecipeImportPipeline\Parsers\Import\JSONParser;

class JSONParserIntegrationTest extends TestCase
{
    public function testParseComplexJson() {
        $input = '{
            "@context": "https://schema.org",
            "@type": "Recipe",
            "name": "Spaghetti Carbonara",
            "author": {
                "@type": "Person",
                "name": "John Doe"
            },
            "cookTime": "PT30M",
            "recipeIngredient": [
                "200g spaghetti",
                "100g pancetta"
            ],
            "recipeInstructions": [
                {
                    "@type": "HowToStep",
                    "text": "Cook the spaghetti."
                },
                {
                    "@type": "HowToStep",
                    "text": "Cook the pancetta until crispy."
                }
            ]
        }';

        // Create the JsonParser
        $jsonParser = new JsonParser(new FlattenJSONParser());

        // Perform the test
        $phpObjects = $jsonParser->parse($input);

        // Assert the results
        $this->assertInstanceOf(JSONArray::class, $phpObjects);

        // Define expected PHP objects manually
        $expectedPhpObjects = new JSONArray([
            new JSONObject([
                '@context' => new JSONString('https://schema.org'),
                '@type' => new JSONString('Recipe'),
                '@id' => new JSONInteger(1),
                'name' => new JSONString('Spaghetti Carbonara'),
                'author' => new JSONObject(['@id' => new JSONInteger(2)]),
                'cookTime' => new JSONString('PT30M'),
                'recipeIngredient' => new JSONArray([
                    new JSONString('200g spaghetti'),
                    new JSONString('100g pancetta')
                ]),
                'recipeInstructions' => new JSONArray([
                    new JSONObject(['@id' => new JSONInteger(3)]),
                    new JSONObject(['@id' => new JSONInteger(4)])
                ])
            ]),
            new JSONObject([
                '@id' => new JSONInteger(2),
                '@type' => new JSONString('Person'),
                'name' => new JSONString('John Doe')
            ]),
            new JSONObject([
                '@id' => new JSONInteger(3),
                '@type' => new JSONString('HowToStep'),
                'text' => new JSONString('Cook the spaghetti.')
            ]),
            new JSONObject([
                '@id' => new JSONInteger(4),
                '@type' => new JSONString('HowToStep'),
                'text' => new JSONString('Cook the pancetta until crispy.')
            ])
        ]);

        $this->assertEquals($expectedPhpObjects, $phpObjects);
    }
}
