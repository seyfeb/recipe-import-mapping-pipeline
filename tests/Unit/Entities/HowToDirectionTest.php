<?php

namespace RecipeImportPipeline\Tests\Unit\Entities;

use RecipeImportPipeline\Entities\HowToDirection;
use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\HowToSupply;
use RecipeImportPipeline\Exceptions\JsonMappingException;

class HowToDirectionTest extends TestCase
{
    public function testFromJsonWithValidData()
    {
        // Test with valid JSON
        $json = [
            'text' => ['Preheat the oven to 220°C.'],
            'supply' => ['oven', '220°C']
        ];
        $direction = HowToDirection::fromJson($json);

        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->Text);

        $supplies = $direction->Supply;
        $this->assertCount(2, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->Name);
        $this->assertEquals('220°C', $supplies[1]->Name);


        // Test with missing 'text' key
        $json = ['supply' => ['oven', '220°C']];
        $direction = HowToDirection::fromJson($json);
        $this->assertEquals([], $direction->Text);
        $this->assertInstanceOf(HowToDirection::class, $direction);

        $supplies = $direction->Supply;
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->Name);
        $this->assertEquals('220°C', $supplies[1]->Name);


        // Test with numeric 'text' value
        $json = ['text' => 123, 'supply' => ['oven', '220°C']];
        $direction = HowToDirection::fromJson($json);
        $this->assertEquals(['123'], $direction->Text);
        $this->assertInstanceOf(HowToDirection::class, $direction);

        $supplies = $direction->Supply;
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->Name);
        $this->assertEquals('220°C', $supplies[1]->Name);


        // Test with missing 'supply' key
        $json = ['text' => ['Preheat the oven to 220°C.']];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->Supply);


        // Test with numeric 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => 123];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->Text);

        $supplies = $direction->Supply;
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertEquals('123', $supplies[0]->Name);
    }

    public function testFromJsonWithComplexSupplyObject()
    {
        // Test with valid JSON including a complex supply object
        $json = [
            'text' => ['Preheat the oven to 220°C.'],
            'supply' => [
                'oven',
                '220°C',
                ['@type' => 'HowToSupply', 'name' => 'flour'],
                ['@type' => 'HowToSupply', 'name' => 'sugar']
            ]
        ];
        $direction = HowToDirection::fromJson($json);

        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->Text);

        $supplies = $direction->Supply;
        $this->assertCount(4, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[2]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[3]);
        $this->assertEquals('oven', $supplies[0]->Name);
        $this->assertEquals('220°C', $supplies[1]->Name);
        $this->assertEquals('flour', $supplies[2]->Name);
        $this->assertEquals('sugar', $supplies[3]->Name);
    }

    public function testFromJsonWithInvalidData()
    {
        // Test with invalid 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => true];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->Supply);


        // Test with invalid 'text' value
        $json = ['text' => [false], 'supply' => ['oven']];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->Text);

        $supplies = $direction->Supply;
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertEquals('oven', $supplies[0]->Name);


        // Test with invalid object as 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => [[['invalid', 'value']]]];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->Supply);
        
        
        // Test with invalid values in 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => ['oven', null, 'spoon']];
        $direction = HowToDirection::fromJson($json);
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->Text);

        $supplies = $direction->Supply;
        $this->assertCount(2, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->Name);
        $this->assertEquals('spoon', $supplies[1]->Name);
    }

}
