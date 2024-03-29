<?php

namespace RecipeImportPipeline\tests\Unit\Entities\SchemaOrg;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;

class HowToSupplyTest extends TestCase
{
    public function testFromJsonWithValidData()
    {
        $json = [
            'name' => 'Test Supply'
        ];
        $supply = HowToSupply::fromJson($json);
        $this->assertInstanceOf(HowToSupply::class, $supply);
        $this->assertEquals('Test Supply', $supply->getName);

        // Test with 'name' array value
        $json = ['name' => ['name1', 'name2']];
        $supply = HowToSupply::fromJson($json);
        $this->assertInstanceOf(HowToSupply::class, $supply);
        $this->assertEquals('name1', $supply->getName);
    }


    public function testFromJsonWithInvalidData()
    {
        // Test with missing 'name' key
        $json = [];
        $supply = HowToSupply::fromJson($json);
        $this->assertNull($supply);

        // Test with null 'name' value
        $json = ['name' => null];
        $supply = HowToSupply::fromJson($json);
        $this->assertNull($supply);

        // Test with non-supported 'name' value
        $json = ['name' => new \stdClass()];
        $supply = HowToSupply::fromJson($json);
        $this->assertNull($supply);

        // Test with invalid JSON structure
        $json = ['invalid_key' => 'Test Supply'];
        $supply = HowToSupply::fromJson($json);
        $this->assertNull($supply);
    }
}
