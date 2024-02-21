<?php

namespace RecipeImportPipeline\tests\Unit\Entities\SchemaOrg;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;

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
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->getText());

        $supplies = $direction->getSupply();
        $this->assertCount(2, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->getName());
        $this->assertEquals('220°C', $supplies[1]->getName());


        // Test with missing 'text' key
        $json = ['supply' => ['oven', '220°C']];
        $direction = HowToDirection::fromJson($json);
        $this->assertEquals([], $direction->getText());
        $this->assertInstanceOf(HowToDirection::class, $direction);

        $supplies = $direction->getSupply();
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->getName());
        $this->assertEquals('220°C', $supplies[1]->getName());


        // Test with numeric 'text' value
        $json = ['text' => 123, 'supply' => ['oven', '220°C']];
        $direction = HowToDirection::fromJson($json);
        $this->assertEquals(['123'], $direction->getText());
        $this->assertInstanceOf(HowToDirection::class, $direction);

        $supplies = $direction->getSupply();
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->getName());
        $this->assertEquals('220°C', $supplies[1]->getName());


        // Test with missing 'supply' key
        $json = ['text' => ['Preheat the oven to 220°C.']];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->getSupply());


        // Test with numeric 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => 123];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->getText());

        $supplies = $direction->getSupply();
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertEquals('123', $supplies[0]->getName());
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
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->getText());

        $supplies = $direction->getSupply();
        $this->assertCount(4, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[2]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[3]);
        $this->assertEquals('oven', $supplies[0]->getName);
        $this->assertEquals('220°C', $supplies[1]->getName);
        $this->assertEquals('flour', $supplies[2]->getName);
        $this->assertEquals('sugar', $supplies[3]->getName);
    }

    public function testFromJsonWithInvalidData()
    {
        // Test with invalid 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => true];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->getSupply());


        // Test with invalid 'text' value
        $json = ['text' => [false], 'supply' => ['oven']];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->getText());

        $supplies = $direction->getSupply();
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertEquals('oven', $supplies[0]->getName);


        // Test with invalid object as 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => [[['invalid', 'value']]]];
        $direction = HowToDirection::fromJson($json);
        $this->assertInstanceOf(HowToDirection::class, $direction);
        $this->assertEmpty($direction->getSupply());
        
        
        // Test with invalid values in 'supply' value
        $json = ['text' => ['Preheat the oven to 220°C.'], 'supply' => ['oven', null, 'spoon']];
        $direction = HowToDirection::fromJson($json);
        $this->assertEquals(['Preheat the oven to 220°C.'], $direction->getText());

        $supplies = $direction->getSupply();
        $this->assertCount(2, $supplies);
        $this->assertInstanceOf(HowToSupply::class, $supplies[0]);
        $this->assertInstanceOf(HowToSupply::class, $supplies[1]);
        $this->assertEquals('oven', $supplies[0]->getName);
        $this->assertEquals('spoon', $supplies[1]->getName);
    }

}
