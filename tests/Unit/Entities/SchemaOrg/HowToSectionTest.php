<?php

namespace RecipeImportPipeline\tests\Unit\Entities\SchemaOrg;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSection;
use RecipeImportPipeline\Entities\SchemaOrg\HowToStep;

class HowToSectionTest extends TestCase
{
    public function testFromJson_ValidJson_ReturnsHowToSectionObject()
    {
        // Sample JSON data
        $json = [
            'description' => ['Mix ingredients'],
            'itemListElement' => [
                'Preheat oven to 350°F',
                ['@type' => 'HowToStep', 'text' => 'Pour mixture into baking pan'],
                ['@type' => 'HowToDirection', 'text' => 'Bake for 30 minutes'],
                [
                    '@type' => 'HowToSection',
                    'description' => ['Prepare frosting'],
                    'itemListElement' => [
                        'Whisk together powdered sugar and milk',
                        ['@type' => 'HowToStep', 'text' => 'Spread frosting over cake']
                    ]
                ]
            ]
        ];

        // Call fromJson method
        $howToSection = HowToSection::fromJson($json);

        // Assertions
        $this->assertInstanceOf(HowToSection::class, $howToSection);
        $this->assertEquals(['Mix ingredients'], $howToSection->Description);
        $this->assertCount(4, $howToSection->ItemListElement);

        // Check the first item
        $this->assertEquals('Preheat oven to 350°F', $howToSection->ItemListElement[0]);

        // Check the second item (HowToStep object)
        $this->assertInstanceOf(HowToStep::class, $howToSection->ItemListElement[1]);
        $this->assertEquals(['Pour mixture into baking pan'], $howToSection->ItemListElement[1]->Text);

        // Check the third item (HowToDirection object)
        $this->assertInstanceOf(HowToDirection::class, $howToSection->ItemListElement[2]);
        $this->assertEquals('Bake for 30 minutes', $howToSection->ItemListElement[2]->Text[0]);

        // Check the fourth item (HowToSection object)
        $this->assertInstanceOf(HowToSection::class, $howToSection->ItemListElement[3]);
        $this->assertEquals(['Prepare frosting'], $howToSection->ItemListElement[3]->Description);
        $this->assertCount(2, $howToSection->ItemListElement[3]->ItemListElement);
        $this->assertEquals('Whisk together powdered sugar and milk', $howToSection->ItemListElement[3]->ItemListElement[0]);
        $this->assertInstanceOf(HowToStep::class, $howToSection->ItemListElement[3]->ItemListElement[1]);
        $this->assertEquals(['Spread frosting over cake'], $howToSection->ItemListElement[3]->ItemListElement[1]->Text);
    }
}
