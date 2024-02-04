<?php

namespace RecipeImportPipeline\tests\Unit\Entities\SchemaOrg;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Entities\SchemaOrg\HowToStep;

class HowToStepTest extends TestCase
{
    public function testFromJson_WithValidJson_ReturnsHowToStepObject()
    {
        // Arrange
        $json = [
            '@type' => 'HowToStep',
            'text' => ['Step 1: Preheat the oven to 350°F.', 'Step 2: Grease a baking sheet.'],
            'itemListElement' => [
                [
                    '@type' => 'HowToDirection',
                    'text' => ['Preheat the oven to 350°F.']
                ],
                [
                    '@type' => 'HowToDirection',
                    'text' => ['Grease a baking sheet.']
                ]
            ]
        ];

        // Act
        $howToStep = HowToStep::fromJson($json);

        // Assert
        $this->assertInstanceOf(HowToStep::class, $howToStep);
        $this->assertEquals(['Step 1: Preheat the oven to 350°F.', 'Step 2: Grease a baking sheet.'], $howToStep->Text);
        $this->assertCount(2, $howToStep->ItemListElement);
        $this->assertInstanceOf(HowToDirection::class, $howToStep->ItemListElement[0]);
        $this->assertInstanceOf(HowToDirection::class, $howToStep->ItemListElement[1]);
    }

    public function testFromJson_WithValidJson_ReturnsHowToStepObject2()
    {
        // Arrange
        $json = [
            '@type' => 'HowToStep',
            'text' => 'Step 1: Preheat the oven to 350°F.'
        ];

        // Act
        $howToStep = HowToStep::fromJson($json);

        // Assert
        $this->assertInstanceOf(HowToStep::class, $howToStep);
        $this->assertEquals(['Step 1: Preheat the oven to 350°F.'], $howToStep->Text);
    }

    public function testFromJson_WithInvalidJson_ReturnsNull()
    {
        // Arrange
        $json = [
            '@type' => 'HowToStep',
            // Missing 'text' field
            'itemListElement' => [
                [
                    '@type' => 'HowToDirection',
                    'text' => ['Preheat the oven to 350°F.']
                ],
                [
                    '@type' => 'HowToDirection',
                    'text' => ['Grease a baking sheet.']
                ]
            ]
        ];

        // Act
        $howToStep = HowToStep::fromJson($json);

        // Assert
        $this->assertNotNull($howToStep);
    }
}
