<?php

namespace RecipeImportPipeline\Tests\Unit\Parsers;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Parsers\Import\FlattenJSONParser;
use RecipeImportPipeline\Parsers\Import\JSONParser;

class JsonParserTest extends TestCase {
    public function testParseFlatJson() {
        $input = '{
            "name": "John",
            "age": 30,
            "is_student": false,
            "height": 175.5
        }';

        // Mock the FlattenJSONParser
        $flattenJsonParserMock = $this->getMockBuilder(FlattenJSONParser::class)
            ->getMock();
        $flattenJsonParserMock->expects($this->once())
            ->method('parse')
            ->with($input)
            ->willReturn([
                1 => [
                    '@id' => 1,
                    'name' => 'John',
                    'age' => 30,
                    'is_student' => false,
                    'height' => 175.5
                ]
            ]);

        // Create the JsonParser with the mocked FlattenJSONParser
        $jsonParser = new JsonParser($flattenJsonParserMock);

        // Perform the test
        $phpObjects = $jsonParser->parse($input);

        // Assert the results
        $this->assertInstanceOf(JSONArray::class, $phpObjects);

        $expectedPhpObjects = new JSONArray([
            new JSONObject([
                '@id' => new JSONInteger(1),
                'name' => new JSONString('John'),
                'age' => new JSONInteger(30),
                'is_student' => new JSONBool(false),
                'height' => new JSONFloat(175.5)
            ])
        ]);

        $this->assertEquals($expectedPhpObjects, $phpObjects);
    }

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

        // Mock the FlattenJSONParser
        $flattenJsonParserMock = $this->getMockBuilder(FlattenJSONParser::class)
            ->getMock();
        $flattenJsonParserMock->expects($this->once())
            ->method('parse')
            ->with($input)
            ->willReturn([
                1 => [
                    '@id' => 1,
                    '@context' => 'https://schema.org',
                    '@type' => 'Recipe',
                    'name' => 'Spaghetti Carbonara',
                    'author' => ['@id' => 2],
                    'cookTime' => 'PT30M',
                    'recipeIngredient' => ["200g spaghetti", "100g pancetta"],
                    'recipeInstructions' => [['@id' => 3], ['@id' => 4]]
                ],
                2 => [
                    '@id' => 2,
                    '@type' => 'Person',
                    'name' => 'John Doe'
                ],
                3 => [
                    '@id' => 3,
                    '@type' => 'HowToStep',
                    'text' => 'Cook the spaghetti.'
                ],
                4 => [
                    '@id' => 4,
                    '@type' => 'HowToStep',
                    'text' => 'Cook the pancetta until crispy.'
                ]
            ]);

        // Create the JsonParser with the mocked FlattenJSONParser
        $jsonParser = new JsonParser($flattenJsonParserMock);

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
